<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use DateTime;

class CardController extends Controller
{
    public function __invoke(Request $request, ApiService $api, $hash = null)
    {

        if ($request->isMethod('post')) {

            $id = $request->input('id');
            $document = $request->input('document');

            $hash = $this->encryptHash([
                'id' => $id,
                'document' => $document
            ]);

            return redirect()->route('card.show', ['hash' => $hash]);
        } 

        if ($hash) {

            try {
                $decrypted = $this->decryptHash($hash);

                $id = $decrypted['id'];
                $document = $decrypted['document'];

                $student = $api->get("/students/{$id}");

                if (!isset($student['data'])) return redirect()->route('form')->with('error', 'Estudante não encontrado (RA ou CPF inválidos).');

                $data = $student['data'];

                if (empty($data['card'])) {
                    return redirect()->route('form')->with('error', 'Estudante não encontrado (RA ou CPF inválidos).');
                }
                
                if ($data['ra'] !== $id || $data['cpf'] !== preg_replace('/\D/', '', $document)) return redirect()->route('form')->with('error', 'Dados não conferem. Verifique o RA e o CPF digitados.');

                return view('card', [
                    'hash' => $hash
                ]);
                
            } catch (\Exception $e) {
                return redirect()->route('form')->with('error', 'Ocorreu um erro ao processar sua solicitação. Tente novamente.');
            }
        }

        return redirect()->route('form');
    }

    public function generateImage(Request $request, ApiService $api, $hash)
    {


        try {
            $decrypted = $this->decryptHash($hash);

            $id = $decrypted['id'];
            $document = $decrypted['document'];

            $student = $api->get("/students/{$id}");

            if (!isset($student['data'])) abort(404);

            $data = $student['data'];
            
            if ($data['ra'] !== $id || $data['cpf'] !== preg_replace('/\D/', '', $document)) abort(404);

            $width = 500;
            $height = 315;

            $image = imagecreatetruecolor($width, $height);

            $backgroundBase64 = env('CARD_MODEL');
            $backgroundData = base64_decode($backgroundBase64);
            $background = imagecreatefromstring($backgroundData);
            
            $bgWidth = imagesx($background);
            $bgHeight = imagesy($background);
            
            imagecopyresampled($image, $background, 0, 0, 0, 0, $width, $height, $bgWidth, $bgHeight);

            $black = imagecolorallocate($image, 0, 0, 0);
            $darkGray = imagecolorallocate($image, 51, 51, 51);
            $gray = imagecolorallocate($image, 102, 102, 102);
            $white = imagecolorallocate($image, 255, 255, 255);
            $lightGray = imagecolorallocate($image, 229, 229, 229);
            $borderWhite = imagecolorallocate($image, 255, 255, 255);

            $fontRegular = public_path('fonts/arial-regular.ttf');
            $fontBold = public_path('fonts/arial-bold.ttf');
  
            // Nome do estudante (maior e em destaque)
            imagettftext($image, 11, 0, 20, 150, $black, $fontBold, strtoupper($data['name']));

            // Data de Nascimento
            imagettftext($image, 8, 0, 20, 180, $black, $fontBold, 'DATA DE NASCIMENTO');
            imagettftext($image, 10, 0, 20, 200, $black, $fontRegular, (new DateTime((string) ($data['birth'])))->format('d/m/Y'));

            // Matrícula
            imagettftext($image, 8, 0, 170, 180, $black, $fontBold, 'MATRÍCULA');
            imagettftext($image, 8, 0, 170, 200, $black, $fontRegular, $data['ra']);

            // Curso
            imagettftext($image, 8, 0, 20, 230, $black, $fontBold, 'CURSO');
            imagettftext($image, 8, 0, 20, 250, $black, $fontRegular, strtoupper($data['enroll']['course']['description']));


            // Turma
            imagettftext($image, 8, 0, 170, 230, $black, $fontBold, 'TURMA');
            imagettftext($image, 8, 0, 170, 250, $black, $fontRegular, $data['enroll']['class']['description']);
         
            // Validade
            $validityText = 'Válido até ' . (new DateTime((string) ($data['card']['expiration'])))->format('d/m/Y');
            imagettftext($image, 8, 0, 320, 295, $white, $fontRegular, $validityText);

            $photoPath = storage_path('app/public/photos/' . $id . '.jpg');
            
            if (file_exists($photoPath)) {
                $photo = imagecreatefromjpeg($photoPath);
                
                $photoDestX = 320;
                $photoDestY = 170; 
                $photoDestW = 100;
                $photoDestH = 100;

                $origW = imagesx($photo);
                $origH = imagesy($photo);
                
                $srcRatio = $origW / $origH;
                $dstRatio = $photoDestW / $photoDestH;
                
                $srcX = 0;
                $srcY = 0;
                $srcW = $origW;
                $srcH = $origH;

                if ($srcRatio > $dstRatio) {
                    $srcW = (int) ($origH * $dstRatio);
                    $srcX = (int) (($origW - $srcW) / 2);
                } else {
                    $srcH = (int) ($origW / $dstRatio);
                    $srcY = (int) (($origH - $srcH) / 2);
                }

                $resized = imagecreatetruecolor($photoDestW, $photoDestH);
                imagecopyresampled($resized, $photo, 0, 0, $srcX, $srcY, $photoDestW, $photoDestH, $srcW, $srcH);
                
                $finalPhoto = imagecreatetruecolor($photoDestW, $photoDestH);
                imagealphablending($finalPhoto, false);
                imagesavealpha($finalPhoto, true);
                $transparent = imagecolorallocatealpha($finalPhoto, 255, 255, 255, 127);
                imagefill($finalPhoto, 0, 0, $transparent);
                
                $radius = $photoDestW / 2;
                
                for ($x = 0; $x < $photoDestW; $x++) {
                    for ($y = 0; $y < $photoDestH; $y++) {
                        $dx = $x - $radius + 0.5;
                        $dy = $y - $radius + 0.5;
                        if ($dx * $dx + $dy * $dy <= $radius * $radius) {
                            $color = imagecolorat($resized, $x, $y);
                            imagesetpixel($finalPhoto, $x, $y, $color);
                        }
                    }
                }
                
                imagecopy($image, $finalPhoto, $photoDestX, $photoDestY, 0, 0, $photoDestW, $photoDestH);
                
                imagedestroy($resized);
                imagedestroy($finalPhoto);
                
                imagedestroy($photo);
            } 

            $validateUrl = route('card.validate', ['hash' => base64_encode($id)]);
            $qrCodeUrl = "https://quickchart.io/qr?text=" . urlencode($validateUrl) . "&size=300&margin=0&ecLevel=L";
            
            // Texto validação QR Code
            $qrText = "Valide a carteirinha\natravés do QR Code";
            $parts = explode("\n", $qrText);
            $textY = 35;
            
            foreach ($parts as $line) {
                $bbox = imagettfbbox(7, 0, $fontRegular, $line);
                $textWidth = $bbox[2] - $bbox[0];
                $textX = 370 - ($textWidth / 2); 
                imagettftext($image, 7, 0, $textX, $textY, $black, $fontRegular, $line);
                $textY += 12;
            }

            try {
                $qrCodeData = @file_get_contents($qrCodeUrl);
                if ($qrCodeData) {
                    $qrCode = imagecreatefromstring($qrCodeData);
                    if ($qrCode) {
                        imagecopyresampled($image, $qrCode, 330, 60, 0, 0, 80, 80, imagesx($qrCode), imagesy($qrCode));
                        imagedestroy($qrCode);
                    }
                }
            } catch (\Exception $e) {
            }

            ob_start();
            imagejpeg($image, null, 95);
            $imageData = ob_get_clean();
            imagedestroy($image);
            imagedestroy($background);

            return response($imageData)
                ->header('Content-Type', 'image/jpeg')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function uploadPhoto(Request $request, $hash)
    {
        try {
            $decrypted = $this->decryptHash($hash);
            $id = $decrypted['id'];

            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120' // 5MB max
            ]);

            $storagePath = storage_path('app/public/photos');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            $fileName = $id . '.jpg';
            $file = $request->file('photo');
            
            $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $origW = imagesx($image);
            $origH = imagesy($image);
            
            $maxDim = 1000;
            if ($origW > $maxDim || $origH > $maxDim) {
                if ($origW > $origH) {
                    $newW = $maxDim;
                    $newH = (int) ($origH * ($maxDim / $origW));
                } else {
                    $newH = $maxDim;
                    $newW = (int) ($origW * ($maxDim / $origH));
                }
                
                $resized = imagecreatetruecolor($newW, $newH);
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
                imagejpeg($resized, $storagePath . '/' . $fileName, 90);
                imagedestroy($resized);
            } else {
                imagejpeg($image, $storagePath . '/' . $fileName, 90);
            }
            
            imagedestroy($image);

            return redirect()->route('card.show', ['hash' => $hash])
                ->with('success', 'Foto adicionada com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao fazer upload da foto: ' . $e->getMessage());
        }
    }

    public function validateCard(Request $request, ApiService $api, $hash)
    {
        try {
            $decrypted = $this->decryptHash($hash);
            $id = $decrypted['id'];
            $document = $decrypted['document'];
            $isOldHash = true;
        } catch (\Exception $e) {
            $decoded = base64_decode($hash);
            if (is_numeric($decoded)) {
                $id = $decoded;
                $isOldHash = false;
            } else {
                return view('validate', ['valid' => false]);
            }
        }

        try {
            $student = $api->get("/students/{$id}");

            if (!isset($student['data'])) {
                return view('validate', ['valid' => false]);
            }

            $data = $student['data'];

            if (empty($data['card'])) {
                return view('validate', ['valid' => false]);
            }
            
            if ($isOldHash) {
               if ($data['ra'] != $id || $data['cpf'] !== preg_replace('/\D/', '', $document)) {
                   return view('validate', ['valid' => false]);
               }
            } else {
               if ($data['ra'] != $id) {
                   return view('validate', ['valid' => false]);
               }
            }

            return view('validate', [
                'valid' => true,
                'data' => $data,
                'id' => $id
            ]);
            
        } catch (\Exception $e) {
            return view('validate', ['valid' => false]);
        }
    }
    private function encryptHash($data)
    {
        // Compact Payload: ID|CPF (Only numbers for CPF to save space)
        $payload = $data['id'] . '|' . preg_replace('/\D/', '', $data['document']);

        // AES-256-GCM (Authenticated Encryption) for minimal size
        // Requires 12 bytes IV, 16 bytes Tag
        $iv = openssl_random_pseudo_bytes(12);
        $key = app('encrypter')->getKey(); // Uses Laravel's APP_KEY
        
        $tag = '';
        $ciphertext = openssl_encrypt($payload, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
        
        // Structure: IV (12) . Tag (16) . Ciphertext (Variable)
        $blob = $iv . $tag . $ciphertext;
        
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($blob));
    }

    private function decryptHash($hash)
    {
        // 1. Decodifica Base64 URL Safe
        $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $hash));

        // 2. Tenta decodificar formato compact (AES-256-GCM)
        // Min size: 12 (IV) + 16 (Tag) + 1 (Payload) = 29 bytes
        if (strlen($decoded) >= 29) {
            $iv = substr($decoded, 0, 12);
            $tag = substr($decoded, 12, 16);
            $ciphertext = substr($decoded, 28);
            
            try {
                $key = app('encrypter')->getKey();
                $plaintext = openssl_decrypt($ciphertext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
                
                if ($plaintext !== false && strpos($plaintext, '|') !== false) {
                    list($id, $document) = explode('|', $plaintext, 2);
                    return ['id' => $id, 'document' => $document];
                }
            } catch (\Exception $e) {
                // Falha silenciosa para tentar outros métodos
            }
        }

        // 3. Fallback: Formato Laravel Padrão (URL Safe ou Base64 normal)
        // Tenta adicionar padding se necessário para o formato base64 original
        try {
             // Re-pad para Crypt::decryptString que exige base64 válido
             $b64 = str_replace(['-', '_'], ['+', '/'], $hash);
             $len = strlen($b64);
             if ($len % 4) {
                  $b64 .= str_repeat('=', 4 - ($len % 4));
             }
             return json_decode(Crypt::decryptString($b64), true);
        } catch (\Exception $e) {}

        // 4. Fallback: Legado "NICETRYBRO"
        try {
            $decodedLegacy = base64_decode($hash);
            if ($decodedLegacy && strpos($decodedLegacy, 'NICETRYBRO') === 0) {
                $encrypted = str_replace('NICETRYBRO', '', $decodedLegacy);
                return json_decode(Crypt::decryptString($encrypted), true);
            }
        } catch (\Exception $e) {}

        throw new \Exception('Invalid Hash');
    }
}
