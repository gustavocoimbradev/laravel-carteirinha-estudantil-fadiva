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

            $user = $request->input('user');
            $pass = $request->input('pass');

            $auth = $api->post("/auth", ['user' => $user, 'pass' => $pass], 'form');

            if ($auth['data']['success']){ 
                $hash = $this->encryptHash([
                    'id' => $user,
                ]);
                $request->session()->put('hash', $hash);
                return redirect()->route('card.show', ['hash' => $hash]);
            } else {
                return redirect()->route('form')->with('error', 'Credenciais inválidas');
            }

        } 

        if ($hash) {

            try {

                if ($request->session()->get('hash') !== $hash) return redirect()->route('form')->with('error', 'Sessão expirada');
                
                $decrypted = $this->decryptHash($hash);

                $id = $decrypted['id'];

                $student = $api->get("/students/{$id}");

                if (!isset($student['data'])) return redirect()->route('form')->with('error', 'Credenciais inválidas');

                $data = $student['data'];

                if (empty($data['card'])) {
                    return redirect()->route('form')->with('error', 'Credenciais inválidas');
                }
                
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

            $student = $api->get("/students/{$id}");

            if (!isset($student['data'])) abort(404);

            $data = $student['data'];
            
            $scale = 3; 
            $width = 500 * $scale;
            $height = 315 * $scale;

            $image = imagecreatetruecolor($width, $height);

            $backgroundBase64 = config('app.card_model');
            $backgroundData = base64_decode($backgroundBase64);
            $background = imagecreatefromstring($backgroundData);
            
            $bgWidth = imagesx($background);
            $bgHeight = imagesy($background);
            
            imagecopyresampled($image, $background, 0, 0, 0, 0, $width, $height, $bgWidth, $bgHeight);

            $black = imagecolorallocate($image, 0, 0, 0);
            $white = imagecolorallocate($image, 255, 255, 255);

            $fontRegular = public_path('fonts/arial-regular.ttf');
            $fontBold = public_path('fonts/arial-bold.ttf');
  
            // Nome do estudante (maior e em destaque)
            imagettftext($image, 11 * $scale, 0, 20 * $scale, 150 * $scale, $black, $fontBold, strtoupper($data['name']));

            // Data de Nascimento
            imagettftext($image, 8 * $scale, 0, 20 * $scale, 180 * $scale, $black, $fontBold, 'DATA DE NASCIMENTO');
            imagettftext($image, 8 * $scale, 0, 20 * $scale, 200 * $scale, $black, $fontRegular, (new DateTime((string) ($data['birth'])))->format('d/m/Y'));

            // Matrícula
            imagettftext($image, 8 * $scale, 0, 170 * $scale, 180 * $scale, $black, $fontBold, 'MATRÍCULA');
            imagettftext($image, 8 * $scale, 0, 170 * $scale, 200 * $scale, $black, $fontRegular, $data['ra']);

            // Curso
            imagettftext($image, 8 * $scale, 0, 20 * $scale, 230 * $scale, $black, $fontBold, 'CURSO');
            imagettftext($image, 8 * $scale, 0, 20 * $scale, 250 * $scale, $black, $fontRegular, strtoupper($data['last_enrollment']['course']['description']));


            // Turma
            imagettftext($image, 8 * $scale, 0, 170 * $scale, 230 * $scale, $black, $fontBold, 'TURMA');
            imagettftext($image, 8 * $scale, 0, 170 * $scale, 250 * $scale, $black, $fontRegular, $data['last_enrollment']['class']['id']);
         
            // Validade
            $validityText = 'Válido até ' . (new DateTime((string) ($data['card']['expiration'])))->format('d/m/Y');
            imagettftext($image, 8 * $scale, 0, 320 * $scale, 295 * $scale, $white, $fontRegular, $validityText);

            if ($id == '00000') {
               $photoPath = public_path('img/jimmy.jpg');
            } else {
               $photoPath = storage_path('app/public/photos/' . $id . '.jpg');
            }
            
            if (!file_exists($photoPath)) {
                $photoPath = public_path('img/no-pic.jpg');
            }

            if (file_exists($photoPath)) {
                $photo = imagecreatefromjpeg($photoPath);
                
                $photoDestX = 320 * $scale;
                $photoDestY = 170 * $scale; 
                $photoDestW = 100 * $scale;
                $photoDestH = 100 * $scale;

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
            $textY = 35 * $scale;
            
            foreach ($parts as $line) {
                $bbox = imagettfbbox(7 * $scale, 0, $fontRegular, $line);
                $textWidth = $bbox[2] - $bbox[0];
                $textX = (370 * $scale) - ($textWidth / 2); 
                imagettftext($image, 7 * $scale, 0, $textX, $textY, $black, $fontRegular, $line);
                $textY += 12 * $scale;
            }

            try {
                $qrCodeData = @file_get_contents($qrCodeUrl);
                if ($qrCodeData) {
                    $qrCode = imagecreatefromstring($qrCodeData);
                    if ($qrCode) {
                        imagecopyresampled($image, $qrCode, 330 * $scale, 60 * $scale, 0, 0, 80 * $scale, 80 * $scale, imagesx($qrCode), imagesy($qrCode));
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
            
            if ($data['ra'] != $id) {
                return view('validate', ['valid' => false]);
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
        $payload = $data['id'];
        $iv = openssl_random_pseudo_bytes(12);
        $key = app('encrypter')->getKey(); 
        $tag = '';
        $ciphertext = openssl_encrypt($payload, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
        $blob = $iv . $tag . $ciphertext;
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($blob));
    }

    private function decryptHash($hash)
    {
        $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $hash));
        if (strlen($decoded) >= 29) {
            $iv = substr($decoded, 0, 12);
            $tag = substr($decoded, 12, 16);
            $ciphertext = substr($decoded, 28);
            try {
                $key = app('encrypter')->getKey();
                $plaintext = openssl_decrypt($ciphertext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
                
                if ($plaintext !== false) {
                    return ['id' => $plaintext]; 
                }
            } catch (\Exception $e) {
                // Falha silenciosa para tentar outros métodos
            }
        }

        try {
             $b64 = str_replace(['-', '_'], ['+', '/'], $hash);
             $len = strlen($b64);
             if ($len % 4) {
                  $b64 .= str_repeat('=', 4 - ($len % 4));
             }
             return json_decode(Crypt::decryptString($b64), true);
        } catch (\Exception $e) {}

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
