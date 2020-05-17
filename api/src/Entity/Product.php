<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Any offered product or service. For example: a pair of shoes; a concert ticket; the rental of a car; a haircut; or an episode of a TV show streamed online.
 *
 * @see http://schema.org/Product Documentation on Schema.org
 *
 * @author Maxim Yalagin <yalagin@gmail.com>
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ApiResource(iri="http://schema.org/Product")
 */
class Product
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @Assert\Type("Ramsey\Uuid\UuidInterface")
     */
    private $id;

    /**
     * @var string the name of the item
     *
     * @ORM\Column(type="text")
     * @ApiProperty(iri="http://schema.org/name")
     * @Assert\Type("string")
     */
    private $name;

    /**
     * @var integer The offer price of a product, or of a price component when attached to PriceSpecification and its subtypes.
     * @Assert\Type("integer")
     * @ORM\Column(type="integer")
     * in real life its should be in cents or kopeck but for sake of this demo it's like that
     */
    private $price;

    /**
     * @var string The currency of the price, or a price component when attached to PriceSpecification and its subtypes. Use standard formats: ISO 4217 currency format e.g. "USD"; Ticker symbol for cryptocurrencies e.g. "BTC"; well known names for Local Exchange Tradings Systems (LETS) and other currency types e.g. "Ithaca HOUR".
     * @Assert\Type("string")
     * @ORM\Column(type="text")
     */
    private $priceCurrency;

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getPriceCurrency(): string
    {
        return $this->priceCurrency;
    }

    /**
     * @param string $priceCurrency
     */
    public function setPriceCurrency(string $priceCurrency): void
    {
        $this->priceCurrency = $priceCurrency;
    }
}
