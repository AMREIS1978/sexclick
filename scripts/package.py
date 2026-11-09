from pathlib import Path
from zipfile import ZipFile,ZIP_DEFLATED
import subprocess,sys
root=Path(__file__).resolve().parents[1]
subprocess.run([sys.executable,str(root/'scripts/compile-translations.py')],check=True)
out=root/'dist';out.mkdir(exist_ok=True)
for name,folder in [('sexclick-theme','wp-content/themes/sexclick'),('sexclick-operations','wp-content/plugins/sexclick-operations')]:
 path=root/folder
 with ZipFile(out/f'{name}.zip','w',ZIP_DEFLATED) as z:
  for f in sorted(path.rglob('*')):
   if f.is_file():z.write(f,Path(path.name)/f.relative_to(path))
with ZipFile(out/'SEXCLICK-1.1-HOSTINGER.zip','w',ZIP_DEFLATED) as z:
 z.write(out/'sexclick-theme.zip','01-TEMA-sexclick.zip')
 z.write(out/'sexclick-operations.zip','02-PLUGIN-sexclick-operations.zip')
 z.write(root/'docs/GUIA-HOSTINGER.md','LEIA-PRIMEIRO.md')
print('Pacotes instaláveis e kit Hostinger 1.1 criados em dist/.')
