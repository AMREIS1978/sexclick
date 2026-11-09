"""Compile the theme's explicit translations into standard WordPress MO files."""
from pathlib import Path
import json, struct
root=Path(__file__).resolve().parents[1]
folder=root/'wp-content/themes/sexclick/languages';folder.mkdir(exist_ok=True)
translations=json.loads((folder/'translations.json').read_text())
for locale,terms in translations.items():
 messages={'':'Content-Type: text/plain; charset=UTF-8\nLanguage: '+locale+'\n',**terms}
 keys=sorted(messages);ids=b'';values=b'';offsets=[]
 for key in keys:
  left=key.encode();right=messages[key].encode();offsets.append((len(left),len(ids),len(right),len(values)));ids+=left+b'\0';values+=right+b'\0'
 count=len(keys);start_ids=28+16*count;start_values=start_ids+len(ids)
 data=struct.pack('<7I',0x950412de,0,count,28,28+8*count,0,0)
 data+=b''.join(struct.pack('<2I',length,start_ids+offset) for length,offset,_,_ in offsets)
 data+=b''.join(struct.pack('<2I',length,start_values+offset) for _,_,length,offset in offsets)
 (folder/(locale+'.mo')).write_bytes(data+ids+values)
print('Traduções do tema compiladas: '+', '.join(translations))
