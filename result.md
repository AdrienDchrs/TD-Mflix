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

- 