from gensim.parsing.porter import PorterStemmer
import sys
import re
import joblib

loaded_combined_model = joblib.load('combined_model.pkl')
p = PorterStemmer()
listStopword = loaded_combined_model['listStopword']

new_document = sys.argv[1]
inpt = re.sub('[,\.\?\!]', ' ', new_document).lower()
wordsinput = [w for w in inpt.split() if not re.search(r'\d', w)]
stopinput = [word for word in wordsinput if word.lower() not in listStopword]
steminput = p.stem_documents(stopinput)
preinput = ' '.join(steminput)
new_document = preinput.strip()
print(new_document)