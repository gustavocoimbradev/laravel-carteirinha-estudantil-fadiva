# Fontes para Carteirinha FADIVA

## Instalação da Fonte Arial (Opcional)

O sistema já está configurado para usar fontes do sistema automaticamente. Porém, para melhor qualidade, você pode adicionar a fonte Arial manualmente:

### Opção 1: Baixar Arial.ttf
1. Baixe o arquivo `Arial.ttf` de uma fonte confiável
2. Coloque o arquivo nesta pasta: `public/fonts/Arial.ttf`

### Opção 2: Usar fontes do sistema (Automático)
O sistema tentará usar automaticamente uma das seguintes fontes do sistema:
- Liberation Sans (Ubuntu/Debian)
- DejaVu Sans (Ubuntu/Debian)
- Arial (Windows/msttcorefonts)

### Instalando fontes Microsoft no Ubuntu/Debian
```bash
sudo apt-get update
sudo apt-get install -y ttf-mscorefonts-installer
```

Após a instalação, a fonte Arial estará disponível em:
`/usr/share/fonts/truetype/msttcorefonts/Arial.ttf`

## Verificação
Para verificar se a fonte está sendo usada corretamente, acesse a carteirinha e verifique se o texto está renderizado com qualidade superior.
