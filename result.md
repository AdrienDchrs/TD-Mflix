### 1. Obtenez le nombre total de films

- db.movies.countDocuments({ type: "movie" }) 
- 23285

### 2. Obtenez le nombre total de séries

- db.movies.countDocuments({ type: "series" })
- 254

### 3. Obtenez les 2 différents types de contenu présents dans la collection movies

- db.movies.distinct("type")
- [ 'movie', 'series' ]

### 4. Obtenez la liste des genres de contenus disponibles dans la collection movies.

- db.movies.distinct("genres")
- [
  'Action',      'Adventure', 'Animation',
  'Biography',   'Comedy',    'Crime',
  'Documentary', 'Drama',     'Family',
  'Fantasy',     'Film-Noir', 'History',
  'Horror',      'Music',     'Musical',
  'Mystery',     'News',      'Romance',
  'Sci-Fi',      'Short',     'Sport',
  'Talk-Show',   'Thriller',  'War',
  'Western'
]

### 5. Récupérez les films depuis 2015 classés par ordre décroissant

- db.movies.find({ year: { $gte: 2015 }, type: "movie" }).sort({ year: -1 })
- {
    _id: ObjectId('573a13e6f29313caabdc6a9a'),
    plot: 'The journey of a professional wrestler who becomes a small town pastor and moonlights as a masked vigilante fighting injustice. While facing crises at home and at the church, the Pastor ...',
    genres: [
        'Action',
        'Biography',
        'Crime'
    ],
    runtime: 111,
    title: 'The Masked Saint',
    num_mflix_comments: 5,
    poster: 'https://m.media-amazon.com/images/M/MV5BMjI0NzU4MjkxNl5BMl5BanBnXkFtZTgwMTYxMTA1NzE@._V1_SY1000_SX677_AL_.jpg',
    countries: [
        'Canada'
    ],
    fullplot: 'The journey of a professional wrestler who becomes a small town pastor and moonlights as a masked vigilante fighting injustice. While facing crises at home and at the church, the Pastor must evade the police and somehow reconcile his violent secret identity with his calling as a pastor.',
    languages: [
        'English'
    ],
    cast: [
        'Brett Granstaff',
        'Lara Jean Chorostecki',
        'T.J. McGibbon',
        'Diahann Carroll'
    ],
    directors: [
        'Warren P. Sonoda'
    ],
    writers: [
        'Scott Crowell',
        'Scott Crowell',
        'Brett Granstaff'
    ],
    awards: {
        wins: 1,
        nominations: 3,
        text: '1 win & 3 nominations.'
    },
    lastupdated: '2015-09-01 01:13:10.960000000',
    year: 2016,
    imdb: {
        rating: '',
        votes: '',
        id: 3103166
    },
    type: 'movie',
    tomatoes: {
        viewer: {
        rating: 0,
        numReviews: 0
        },
        lastUpdated: 2014-12-08T03:07:09.000Z
    }
}
- "Résultat trop long, je n'inscris que le premier résultat"

### 6. Obtenez le nombre de films sortis depuis 2015 ayant remporté au moins 5 récompenses

- db.movies.countDocuments({year: { $gte: 2015 }, type: "movie", "awards.wins": { $gte: 5 }})
- 33 

### 7. Parmi ces films, indiquez le nombre de films disponibles en français

- db.movies.countDocuments({ year: { $gte: 2015 }, type: "movie", "awards.wins": { $gte: 5 }, languages: "French"})
- 1

### 8. Sélectionnez les films dont le genre est Thriller et Drama et indiquez leur nombre

- db.movies.countDocuments({ genres: { $all: ["Thriller", "Drama"] }})
- 1245

### 9. Sélectionnez le titre et les genres des films dont le genre est Crime ou Thriller

- db.movies.find({ genres: { $in: ["Crime", "Thriller"] } }, { title: 1, genres: 1, _id: 0 })
- {
  genres: ['Crime', 'Drama'],
  title: 'Traffic in Souls'
}
- "Résultat trop long je mets que la première occurrence"

### 10. Sélectionnez le titre et les langues des films disponibles en français et en italien

- db.movies.find({ languages: { $all: ["French", "Italian"] } }, { title: 1, languages: 1, _id: 0 })
- {
  title: 'Morocco',
  languages: ['English', 'French', 'Spanish', 'Arabic', 'Italian']
}
- "Résultat trop long je mets que la première occurrence"

### 11. Sélectionnez le titre et le genre des films dont la note IMDB est supérieure à 9

- db.movies.find({ "imdb.rating": { $gt: 9 } }, { title: 1, genres: 1, _id: 0 })
- {
  genres: ['Crime','Drama'],
  title: 'The Godfather'
}
- "Résultat trop long je mets que la première occurrence"

### 12. Affichez le nombre de contenus dont le nombre d'acteurs au casting est égal à 4

- db.movies.countDocuments({"cast": { $size: 4 }})
- 22389

### 13. Affichez : Le nombre de contenus (count), Le nombre total de récompense(totalAwards), Le nombre moyen de nominations (averageNominations), Le nombre moyen de récompenses (averageAwards) pour l'ensemble des contenus de la collection movies.

- db.movies.aggregate([ { $group: { _id: null,
      count: { $sum: 1 },
      totalAwards: { $sum: "$awards.wins" },
      averageNominations: { $avg: "$awards.nominations" },
      averageAwards: { $avg: "$awards.wins" }
    }
  }
])

- {
  _id: null,
  count: 23539,
  totalAwards: 96770,
  averageNominations: 4.776031267258592,
  averageAwards: 4.111049747228004
}

### 14. Affichez le nombre d'acteurs au casting (castTotal) pour chaque contenu :
- db.movies.aggregate([
  {
    $project: {
      title: 1,
      castTotal: { $size: "$cast" }
    }
  }
])

- {
  _id: ObjectId('573a1390f29313caabcd4135'),
  title: 'Blacksmith Scene',
  castTotal: 2
}
- "Résultat trop long je mets que la première occurrence"

### 15. Calculez le nombre de fois que le terme "Hollywood" apparaît dans le résumé des contenus (fullplot):

- db.movies.aggregate([
  {
    $project: {
      fullplot: 1,
      hollywoodCount: {
        $size: {
          $filter: {
            input: {
              $cond: {
                if: { $isArray: { $split: ["$fullplot", " "] } },
                then: { $split: ["$fullplot", " "] },
                else: []
              }
            },
            as: "word",
            cond: { $eq: ["$$word", "Hollywood"] }
          }
        }
      }
    }
  },
  {
    $group: {
      _id: null,
      totalHollywood: { $sum: "$hollywoodCount" }
    }
  }
])

- {
  _id: null,
  totalHollywood: 191
}

### 16. Trouvez les films sortis entre 2000 et 2010 qui ont une note IMDB supérieure à 8 et plus de 10 récompenses.

- db.movies.find({
  "year": { $gte: 2000, $lte: 2010 },
  "imdb.rating": { $gt: 8 },
  "awards.wins": { $gt: 10 }
})

- {
  _id: ObjectId('573a139af29313caabcf0782'),
  fullplot: 'Set in Hong Kong, 1962, Chow Mo-Wan is a newspaper editor who moves into a new building with his wife. At the same time, Su Li-zhen, a beautiful secretary and her executive husband also move in to the crowded building. With their spouses often away, Chow and Li-zhen spend most of their time together as friends. They have everything in common from noodle shops to martial arts. Soon, they are shocked to discover that their spouses are having an affair. Hurt and angry, they find comfort in their growing friendship even as they resolve not to be like their unfaithful mates.',
  imdb: {
    rating: 8.1,
    votes: 67663,
    id: 118694
  },
  year: 2000,
  plot: 'Two neighbors, a woman and a man, form a strong bond after both suspect extramarital activities of their spouses. However, they agree to keep their bond platonic so as not to commit similar wrongs.',
  genres: [
    'Drama',
    'Romance'
  ],
  rated: 'PG',
  metacritic: 85,
  title: 'In the Mood for Love',
  lastupdated: '2015-09-15 05:14:09.273000000',
  languages: [
    'Cantonese',
    'Shanghainese',
    'French'
  ],
  writers: [
    'Kar Wai Wong'
  ],
  type: 'movie',
  tomatoes: {
    website: 'http://www.wkw-inthemoodforlove.com',
    viewer: {
      rating: 4.3,
      numReviews: 51804,
      meter: 94
    },
    dvd: 2002-03-05T00:00:00.000Z,
    critic: {
      rating: 7.8,
      numReviews: 119,
      meter: 90
    },
    lastUpdated: 2015-09-15T17:08:11.000Z,
    consensus: 'This understated romance, featuring good performances by its leads, is both visually beautiful and emotionally moving.',
    rotten: 12,
    production: 'USA Films',
    fresh: 107
  },
  poster: 'https://m.media-amazon.com/images/M/MV5BYjZjODRlMjQtMjJlYy00ZDBjLTkyYTQtZGQxZTk5NzJhYmNmXkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_SY1000_SX677_AL_.jpg',
  num_mflix_comments: 1,
  released: 2001-03-09T00:00:00.000Z,
  awards: {
    wins: 49,
    nominations: 33,
    text: 'Nominated for 1 BAFTA Film Award. Another 48 wins & 33 nominations.'
  },
  countries: [
    'Hong Kong',
    'China'
  ],
  cast: [
    'Maggie Cheung',
    'Tony Chiu Wai Leung',
    'Ping Lam Siu',
    "Tung Cho 'Joe' Cheung"
  ],
  directors: [
    'Kar Wai Wong'
  ],
  runtime: 98
}
- "Résultat trop long je mets que la première occurrence"

### 17. Proposez une nouvelle question complexe et fournissez la solution pour y répondre ainsi que la réponse.

- db.movies.find({
  "year": { $gte: 2000, $lte: 2015 },
  "directors": "Steven Spielberg",
  "awards.nominations": { $gt: 20 },
  "imdb.rating": { $gt: 7.5 }
})

- {
  _id: ObjectId('573a139ff29313caabcff3cf'),
  fullplot: `In the year 2054 A.D. crime is virtually eliminated from Washington D.C. thanks to an elite law enforcing squad "Precrime". They use three gifted humans (called "Pre-Cogs") with special powers to see into the future and predict crimes beforehand. John Anderton heads Precrime and believes the system's flawlessness steadfastly. However one day the Pre-Cogs predict that Anderton will commit a murder himself in the next 36 hours. Worse, Anderton doesn't even know the victim. He decides to get to the mystery's core by finding out the 'minority report' which means the prediction of the female Pre-Cog Agatha that "might" tell a different story and prove Anderton innocent.`,
  imdb: {
    rating: 7.7,
    votes: 369160,
    id: 181689
  },
  year: 2002,
  plot: 'In a future where a special police unit is able to arrest murderers before they commit their crimes, an officer from that unit is himself accused of a future murder.',
  genres: [
    'Action',
    'Mystery',
    'Sci-Fi'
  ],
  rated: 'PG-13',
  metacritic: 80,
  title: 'Minority Report',
  lastupdated: '2015-09-04 00:02:16.007000000',
  languages: [
    'English',
    'Swedish'
  ],
  writers: [
    'Philip K. Dick (short story)',
    'Scott Frank (screenplay)',
    'Jon Cohen (screenplay)'
  ],
  type: 'movie',
  tomatoes: {
    website: 'http://www.minorityreport.com',
    viewer: {
      rating: 3.4,
      numReviews: 478637,
      meter: 80
    },
    dvd: 2002-12-17T00:00:00.000Z,
    critic: {
      rating: 8.1,
      numReviews: 239,
      meter: 90
    },
    boxOffice: '$131.9M',
    consensus: 'Thought-provoking and visceral, Steven Spielberg successfully combines high concept ideas and high octane action in this fast and febrile sci-fi thriller.',
    rotten: 23,
    production: 'Dreamworks',
    lastUpdated: 2015-09-12T17:29:01.000Z,
    fresh: 216
  },
  poster: 'https://m.media-amazon.com/images/M/MV5BZTI3YzZjZjEtMDdjOC00OWVjLTk0YmYtYzI2MGMwZjFiMzBlXkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_SY1000_SX677_AL_.jpg',
  num_mflix_comments: 3,
  released: 2002-06-21T00:00:00.000Z,
  awards: {
    wins: 18,
    nominations: 66,
    text: 'Nominated for 1 Oscar. Another 17 wins & 66 nominations.'
  },
  countries: [
    'USA'
  ],
  cast: [
    'Tom Cruise',
    'Max von Sydow',
    'Steve Harris',
    'Neal McDonough'
  ],
  directors: [
    'Steven Spielberg'
  ],
  runtime: 145
}