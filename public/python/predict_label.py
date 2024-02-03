from gensim.parsing.porter import PorterStemmer
import sys
import re
import joblib

p = PorterStemmer()
loaded_combined_model = joblib.load('combined_model.pkl')
loaded_kmeans = loaded_combined_model['kmeans']
loaded_classifiers = loaded_combined_model['classifiers_label']
tfidf_vectorizer = loaded_combined_model['tfidf']
listStopword = loaded_combined_model['listStopword']

new_document = sys.argv[1]
inpt = re.sub('[,\.\?\!]', ' ', new_document).lower()
wordsinput = [w for w in inpt.split() if not re.search(r'\d', w)]
stopinput = [word for word in wordsinput if word.lower() not in listStopword]
stopinput = p.stem_documents(stopinput)
preinput = ' '.join(stopinput)
new_document = preinput.strip()

new_tfidf = tfidf_vectorizer.transform([new_document])
new_cluster = loaded_kmeans.predict(new_tfidf)[0]
predicted_class = loaded_classifiers[new_cluster].predict(new_tfidf)[0]
print(predicted_class)
