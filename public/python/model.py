from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.cluster import KMeans
from sklearn.naive_bayes import MultinomialNB
import mysql.connector as connection
import pandas as pd
import joblib

import spacy

sp = spacy.load('en_core_web_sm')
listStopword = sp.Defaults.stop_words
negative_words = ['not', 'cannot', 'bad', 'never', 'no', 'none', 'nobody', 'nowhere', 'nothing', 'noway', 
                  'hardly', 'scarcely', 'rarely']
negative_words.extend(['un', 'non', 'anti', 'dis', 'mis', 'un-', 'in-', 'im-', 'il-', 'ir-', 'mal-', 'anti-', 
                       'dis-', 'mis-'])
listStopword = [word for word in listStopword if word.lower() not in negative_words]

mydb = connection.connect(host="localhost", database = 'ta160420121',user="root", passwd="")
query = "Select * from trainingdata;"
df = pd.read_sql(query,mydb)
mydb.close()

documents = df['preprocessed'].values.tolist()
labels = df['label'].values.tolist()
categories = df['category'].values.tolist()

tfidf_vectorizer = TfidfVectorizer()
tfidf_matrix = tfidf_vectorizer.fit_transform(documents)

k = 2
kmeans = KMeans(n_clusters=k,max_iter=150,random_state=42)
cluster_assignments = kmeans.fit_predict(tfidf_matrix)

classifiersLabel = []
classifiersCategories= []

for i in range(k):
    cluster_documents = [documents[j] for j, cluster in enumerate(cluster_assignments) if cluster == i]
    cluster_labels = [labels[j] for j, cluster in enumerate(cluster_assignments) if cluster == i]

    nb_classifier = MultinomialNB()
    tfidf_matrix_cluster = tfidf_vectorizer.transform(cluster_documents)
    nb_classifier.fit(tfidf_matrix_cluster, cluster_labels)
    classifiersLabel.append(nb_classifier)

for ii in range(k):
    cluster_documents2 = [documents[j] for j, cluster in enumerate(cluster_assignments) if cluster == ii]
    cluster_labels2 = [categories[j] for j, cluster in enumerate(cluster_assignments) if cluster == ii]

    nb_classifier2 = MultinomialNB()
    tfidf_matrix_cluster2 = tfidf_vectorizer.transform(cluster_documents2)
    nb_classifier2.fit(tfidf_matrix_cluster2, cluster_labels2)
    classifiersCategories.append(nb_classifier2)

combined_model = {
    'kmeans': kmeans,
    'classifiers_label': classifiersLabel,
    'tfidf': tfidf_vectorizer,
    'classifiers_category':classifiersCategories,
    'listStopword':listStopword
}

joblib.dump(combined_model, 'combined_model.pkl')

print('Model Saved')
