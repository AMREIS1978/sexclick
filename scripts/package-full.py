"""Build a fresh-site upload ZIP, with the official WordPress index.php at its root.

Download official distribution archives into vendor-cache/ first. No database,
password, wp-config.php or live customer data is included.
"""
from pathlib import Path, PurePosixPath
from zipfile import ZipFile, ZipInfo, ZIP_DEFLATED
import hashlib, json, subprocess, sys

root=Path(__file__).resolve().parents[1]
subprocess.run([sys.executable,str(root/'scripts/compile-translations.py')],check=True)
out=root/'dist';out.mkdir(exist_ok=True)
target=out/'SEXCLICK-SITE-COMPLETO-HOSTINGER-1.2.zip'
sources=[(root/'vendor-cache/wordpress.zip','wordpress/',''),(root/'vendor-cache/woocommerce.zip','woocommerce/','wp-content/plugins/woocommerce/')]
seen=set()
with ZipFile(target,'w',ZIP_DEFLATED,compresslevel=9) as dest:
 for archive,prefix,output in sources:
  with ZipFile(archive) as source:
   if source.testzip() is not None: raise ValueError('Arquivo de origem corrompido: '+archive.name)
   for item in source.infolist():
    if item.is_dir(): continue
    path=PurePosixPath(item.filename)
    if path.is_absolute() or '..' in path.parts or not item.filename.startswith(prefix): raise ValueError('Caminho inválido no arquivo')
    name=output+item.filename[len(prefix):]
    if name=='wp-config.php' or name in seen: raise ValueError('Configuração privada ou ficheiro duplicado')
    seen.add(name)
    info=ZipInfo(name,item.date_time);info.compress_type=ZIP_DEFLATED;info.external_attr=0o100644 << 16
    dest.writestr(info,source.read(item),compresslevel=9)
 for folder in ['wp-content/themes/sexclick','wp-content/plugins/sexclick-operations','wp-content/mu-plugins']:
  for file in sorted((root/folder).rglob('*')):
   if file.is_file():
    name=file.relative_to(root).as_posix()
    if name in seen: raise ValueError('Ficheiro duplicado: '+name)
    seen.add(name);dest.write(file,name)
 dest.write(root/'docs/CARREGAMENTO-COMPLETO.md','LEIA-PRIMEIRO-SEXCLICK.md')
 metadata={'sources':{'WordPress':'https://wordpress.org/latest.zip','WooCommerce':'https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip'},'sha256':{a.name:hashlib.sha256(a.read_bytes()).hexdigest() for a,_,_ in sources},'contains_database':False,'contains_credentials':False}
 dest.writestr('SEXCLICK-PACOTE.json',json.dumps(metadata,indent=2))
with ZipFile(target) as check:
 assert check.testzip() is None
 for required in ['index.php','wp-admin/install.php','wp-admin/setup-config.php','wp-load.php','wp-includes/version.php','wp-content/plugins/woocommerce/woocommerce.php','wp-content/themes/sexclick/style.css','wp-content/mu-plugins/sexclick-first-run.php']:
  assert required in check.namelist(),required
 assert 'wp-config.php' not in check.namelist()
print(f'{target}: {target.stat().st_size:,} bytes; index.php na raiz, WooCommerce e SEXCLICK incluídos; sem base de dados ou credenciais.')
