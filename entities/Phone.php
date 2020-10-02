<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phone")
 * @ORM\HasLifecycleCallbacks
 */
class Phone
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $country_code;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $time_zone;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $inserted_on;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updated_on;

    // Dictionary of country codes.
    private static $country_codes = [];

    // Dictionary of time zones.
    private static $time_zones = [];

    /**
     * Get phone ID.
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get first name.
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * Set first name.
     * @param string $first_name First name
     * @return void
     */
    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * Get last name.
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * Set last name.
     * @param string $last_name Last name
     * @return void
     */
    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * Get phone number.
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    /**
     * Set phone number.
     * @param string $phone_number Phone number
     * @return void
     */
    public function setPhoneNumber(string $phone_number): void
    {
        $this->phone_number = $phone_number;
    }

    /**
     * Get country code.
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    /**
     * Set country code.
     * @param string $country_code Country code
     * @return void
     */
    public function setCountryCode(string $country_code): void
    {
        $this->country_code = $country_code;
    }

    /**
     * Get time zone.
     * @return string
     */
    public function getTimeZone(): string
    {
        return $this->time_zone;
    }

    /**
     * Set time zone.
     * @param string $time_zone Time zone
     * @return void
     */
    public function setTimeZone(string $time_zone): void
    {
        $this->time_zone = $time_zone;
    }

    /**
     * Get time of the inserted record.
     * @return \DateTime
     */
    public function getInsertedOn(): \DateTime
    {
        return $this->inserted_on;
    }

    /**
     * Set time of the inserted record.
     * @param \DateTime $inserted_on Time
     * @return void
     */
    public function setInsertedOn(\DateTime $inserted_on): void
    {
        $this->inserted_on = $inserted_on;
    }

    /**
     * Get time of the updated record.
     * @return \DateTime
     */
    public function getUpdatedOn(): \DateTime
    {
        return $this->updated_on;
    }

    /**
     * Set time of the updated record.
     * @param \DateTime $updated_on Time
     * @return void
     */
    public function setUpdatedOn(\DateTime $updated_on): void
    {
        $this->updated_on = $updated_on;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prepation()
    {
        self::fillingDictionaries();

        $reg_exp = '|^[+]?([(]?[0-9]{1,4}[)]?\s*){0,2}[-\s\./0-9]+$|';

        if (!preg_match($reg_exp, $this->phone_number)) {
            throw new \Exception('The phone number is incorrect!');
        }
        if (!empty(self::$country_codes) && !in_array($this->country_code, self::$country_codes)) {
            throw new \Exception('The country code is incorrect!');
        }
        if (!empty(self::$time_zones) && !in_array($this->time_zone, self::$time_zones)) {
            throw new \Exception('The time zone is incorrect!');
        }

        if (is_null($this->id)) {
            $this->inserted_on = new \DateTime();
        }

        $this->updated_on = new \DateTime();
    }

    /**
     * Filling inner dictionaries with Country Code and Time Zone data.
     */
    private static function fillingDictionaries()
    {
        $DS = DIRECTORY_SEPARATOR;

        if (empty(self::$country_codes)) {
            $file = __DIR__ . $DS . '..' . $DS . 'cache' . $DS . 'countries.json';

            if (!file_exists($file)) {
                $data = @file_get_contents('https://api.hostaway.com/countries');

                if ($data) {
                    file_put_contents($file, $data);
                }
            }

            if (file_exists($file)) {
                $data = json_decode(file_get_contents($file), true);

                self::$country_codes = array_keys($data['result']);
            }
        }
        if (empty(self::$time_zones)) {
            $file = __DIR__ . $DS . '..' . $DS . 'cache' . $DS . 'timezones.json';

            if (!file_exists($file)) {
                $data = @file_get_contents('https://api.hostaway.com/timezones');

                if ($data) {
                    file_put_contents($file, $data);
                }
            }

            if (file_exists($file)) {
                $data = json_decode(file_get_contents($file), true);

                self::$time_zones = array_keys($data['result']);
            }
        }
    }

    /**
     * Get object properties to array.
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'country_code' => $this->country_code,
            'time_zone' => $this->time_zone,
            'inserted_on' => $this->inserted_on ? $this->inserted_on->format(\DateTime::W3C) : null,
            'updated_on' => $this->updated_on ? $this->updated_on->format(\DateTime::W3C) : null
        ];
    }

    /**
     * Set object properties from array.
     * @param array $array Properties
     */
    public function fromArray($array)
    {
        $this->first_name = $array['first_name'] ?? null;
        $this->last_name = $array['last_name'] ?? null;
        $this->phone_number = $array['phone_number'] ?? null;
        $this->country_code = $array['country_code'] ?? null;
        $this->time_zone = $array['time_zone'] ?? null;
    }
}