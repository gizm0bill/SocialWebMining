#!/usr/env/python

from nltk.corpus import wordnet as wn
from nltk.stem.porter import *
import sys

if  __name__ == '__main__' :
    # needs 2 words as arguments
    if( len(sys.argv) < 3 ) : exit(0)
    # get the words
    word1 = sys.argv[1]
    word2 = sys.argv[2]
    # stem the words
    stemmer = PorterStemmer()
    word1 = stemmer.stem(word1)
    word2 = stemmer.stem(word2)
    
    # get word senses
    maxsimil = 0
    similsense1 = simisense2 = None
    for sense1 in wn.synsets(word1) :
        for sense2 in wn.synsets(word2) : 
            simil = sense1.wup_similarity(sense2)
            if simil > maxsimil : 
                maxsimil = simil
                similsense1 = sense1
                similsense2 = sense2
                
    print maxsimil, similsense1, similsense2
                 
     