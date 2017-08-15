<?php
/**
 * Team Class
 *
 */
class Team {
    // Configuration file name.
    private $configFile = 'config.json';
    
    private $gymDataFile = 'gyms.json';

    // Configuration. (object)
    private $config;
    
    public $gymData;

    /**
     * Default constructor.
     */
    public function __construct()
    {
        // First get the configuration.
        $this->getConfiguration();
        
        $this->getGymData();
    }

    /**
     * Get configuration.
     */
    private function getConfiguration()
    {
        // Get names from json file and set var.
        $this->config = json_decode(file_get_contents($this->configFile));
    }

    /**
     * Get getGymData.
     */
    private function getGymData()
    {
        // Get names from json file and set var.
        $this->gymData = json_decode(file_get_contents($this->gymDataFile));
    }

    /**
     * Get url by curl.
     * @param $url
     * @return mixed
     */
    private function curl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0); // Don't return headers.
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $data = curl_exec($ch);

        curl_close($ch);

        return json_decode($data);
    }

    /**
     * Build url.
     * @return string
     */
    private function buildUrl()
    {
        // Build query.
        $query = http_build_query(
            array(
                'gid' => '0',
                'mid' => '99999999999999999',
                'w'   => $this->config->map->boundWest,
                'e'   => $this->config->map->boundEast,
                'n'   => $this->config->map->boundNorth,
                's'   => $this->config->map->boundSouth
            )
        );

        // Build url.
        $url = $this->config->map->url . '/m.php?' . $query;

        return $url;
    }

    /**
     * Get data.
     * @return array
     */
    private function getData()
    {
        // Build url.
        $url = $this->buildUrl();

        // Get data by curl.
        return $this->curl($url);
    }

    /**
     * Collect GymIds
     */
    public function getGymIds()
    {
        $array = array();

        // Get data.
        $result = $this->getData();

        // Valid data received.
        if (!empty($result) && !empty($result->gyms)) {
            // Check each gym.
            foreach ($result->gyms AS $gym) {

                array_push($array,
                    array(
                    'gymId'         => $gym->gym_id,
                    'latitude'      => $gym->latitude,
                    'longitude'     => $gym->longitude
                    )
                );
            }
        }

        return $array;
    }

    /**
     * Get statistic.
     * @param $method
     * @return array
     */
    public function getStatistic($method)
    {
        $stats = array();

        // Get data.
        $result = $this->getData();

        // Valid data received.
        if (!empty($result) && !empty($result->gyms)) {

            $team = array(
                '',
                'blau',
                'rot',
                'gelb'
            );

            // Init trainers.
            $stats['trainers'] = array();


            // Init counters.
            $stats['counter']                       = array();
            $stats['counter']['arenas']             = array();
            $stats['counter']['arenas']['all']      = count($result->gyms);
            $stats['counter']['players']            = array();

            // Check each gym.
            foreach ($result->gyms AS $gym) {
                // Trainers found.
                if (!empty($gym->memb)) {
                    // Foreach trainer.
                    foreach ($gym->memb AS $trainer) {
                        // New trainer found.
                        if (empty($stats['trainers'][$trainer->tn])) {
                            // Create empty trainer array.
                            $stats['trainers'][$trainer->tn] = array();
                            // Init arena counter.
                            $stats['trainers'][$trainer->tn]['counter'] = 0;
                            // Init arena array.
                            $stats['trainers'][$trainer->tn]['arenas'] = array();
                            // Set trainers level.
                            $stats['trainers'][$trainer->tn]['level'] = $trainer->tl;
                            // Set trainer team.
                            $stats['trainers'][$trainer->tn]['team'] = $team[$gym->team_id];

                            // Init player team counter.
                            if (empty($stats['counter']['players'][$team[$gym->team_id]])) {
                                $stats['counter']['players'][$team[$gym->team_id]] = 0;
                            }

                            // Increase player team counter.
                            $stats['counter']['players'][$team[$gym->team_id]]++;

                        }

                        // Increase gym counter.
                        $stats['trainers'][$trainer->tn]['counter'] = $stats['trainers'][$trainer->tn]['counter'] + 1;
                        // Push gym id into arenas array.
                        array_push($stats['trainers'][$trainer->tn]['arenas'],
                            array(
                                'gymId'         => $gym->gym_id,
                                'gymName'       => $gym->name,
                                'latitude'      => $gym->latitude,
                                'longitude'     => $gym->longitude,
                                'gymPoints'     => $gym->gym_points,
                                'timestamp'     => $gym->ts,
                                'members'       => count($gym->memb)
                            )
                        );
                    }
                }

                if ($method == 'top') {
                    $sort = array();

                    // Sort by arena counter.
                    foreach ($stats['trainers'] as $key => $row) {
                        $sort['counter'][$key] = $row['counter'];
                        $sort['level'][$key] = $row['level'];
                    }
                    array_multisort($sort['counter'], SORT_DESC, $sort['level'], SORT_DESC, $stats['trainers']);
                } else {
                    ksort($stats['trainers']);
                }

                // Count all trainers.
                $stats['counter']['players']['all'] = count($stats['trainers']);

                // Init team arena counter.
                if (empty($stats['counter']['arenas'][$team[$gym->team_id]])) {
                    $stats['counter']['arenas'][$team[$gym->team_id]] = 0;
                }
                // Increase team arena counter.
                $stats['counter']['arenas'][$team[$gym->team_id]]++;
            }
        }

        // Return them.
        return $stats;
    }
}