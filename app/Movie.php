<?php

class Movie
{
    public ?string $_id;
    public string $title;
    public string $plot;
    public array $genres;
    public int $runtime;
    public array $cast;
    public int $num_mflix_comments;
    public string $fullplot;
    public array $countries;
    public ?DateTime $released;
    public array $directors;
    public string $rated;
    public array $awards;
    public string $lastupdated;
    public int $year;
    public array $imdb;
    public string $type;
    public array $tomatoes;

    public function __construct(array $data)
    {
        $this->_id = $data['_id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->plot = $data['plot'] ?? '';
        $this->genres = $data['genres'] ?? [];
        $this->runtime = $data['runtime'] ?? 0;
        $this->cast = $data['cast'] ?? [];
        $this->num_mflix_comments = $data['num_mflix_comments'] ?? 0;
        $this->fullplot = $data['fullplot'] ?? '';
        $this->countries = $data['countries'] ?? [];
        $this->released = isset($data['released']) ? new DateTime($data['released']) : null;
        $this->directors = $data['directors'] ?? [];
        $this->rated = $data['rated'] ?? '';
        $this->awards = $data['awards'] ?? [];
        $this->lastupdated = $data['lastupdated'] ?? '';
        $this->year = $data['year'] ?? 0;
        $this->imdb = $data['imdb'] ?? [];
        $this->type = $data['type'] ?? '';
        $this->tomatoes = $data['tomatoes'] ?? [];
    }

    public function toArray(): array
    {
        return [
            '_id' => $this->_id,
            'title' => $this->title,
            'plot' => $this->plot,
            'genres' => $this->genres,
            'runtime' => $this->runtime,
            'cast' => $this->cast,
            'num_mflix_comments' => $this->num_mflix_comments,
            'fullplot' => $this->fullplot,
            'countries' => $this->countries,
            'released' => $this->released ? $this->released->format(DateTime::ATOM) : null,
            'directors' => $this->directors,
            'rated' => $this->rated,
            'awards' => $this->awards,
            'lastupdated' => $this->lastupdated,
            'year' => $this->year,
            'imdb' => $this->imdb,
            'type' => $this->type,
            'tomatoes' => $this->tomatoes,
        ];
    }
}

?>
