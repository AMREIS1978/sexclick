from pathlib import Path
from zipfile import ZipFile,ZIP_DEFLATED
root=Path(__file__).resolve().parents[1]
out=root/'dist';out.mkdir(exist_ok=True)
for name,folder in [('sexclick-theme','wp-content/themes/sexclick'),('sexclick-operations','wp-content/plugins/sexclick-operations')]:
 path=root/folder
 with ZipFile(out/f'{name}.zip','w',ZIP_DEFLATED) as z:
  for f in sorted(path.rglob('*')):
   if f.is_file():z.write(f,Path(path.name)/f.relative_to(path))
print('Pacotes instaláveis criados em dist/.')
