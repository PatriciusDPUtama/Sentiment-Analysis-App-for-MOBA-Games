from google_play_scraper import *
import numpy as np
import pandas as pd
import json
import sys
from deep_translator import GoogleTranslator
from json import loads, dumps

packagename = sys.argv[1]
number_crawl = int(sys.argv[2])

us_reviews,continuation_token = reviews(
    packagename,
    country='id',
    count=number_crawl,
    sort=Sort.NEWEST
)

df = pd.DataFrame(us_reviews)
translated = []
for column in df:
  if(column == 'content'):
    for data in df[column]:
      tlTest = GoogleTranslator(source='auto', target='en').translate(data)
      translated.append(tlTest)

df['translated_content'] = translated
result = df.to_json(orient="records")

parsed = loads(result)
print(dumps(parsed, indent=4)) 