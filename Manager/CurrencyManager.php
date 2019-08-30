<?php

namespace RedCode\CurrencyRateBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RedCode\Currency\ICurrency;
use RedCode\Currency\ICurrencyManager;
use RedCode\CurrencyRateBundle\Entity\Currency;


class CurrencyManager implements ICurrencyManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $currencyClassName;

    /**
     * CurrencyManager constructor.
     * @param EntityManagerInterface $em
     * @param $currencyClassName
     * @throws Exception
     */
    public function __construct(EntityManagerInterface $em, $currencyClassName)
    {
        $this->em = $em;
        $this->currencyClassName = $currencyClassName;
        if (!$currencyClassName || (!$this->em->getMetadataFactory()->hasMetadataFor($currencyClassName) && !$this->em->getClassMetadata($currencyClassName))) {
            throw new Exception("Class for currency \"{$currencyClassName}\" not found");
        }
    }

    /**
     * @param string $code
     * @return object|ICurrency|null
     */
    public function getCurrency($code)
    {
        return $this->em->getRepository($this->currencyClassName)->findOneBy(['code' => $code]);
    }

    /**
     * @return array|ICurrency[]
     */
    public function getAll()
    {
        return $this->em->getRepository($this->currencyClassName)->findAll();
    }

    /**
     * @param string $code
     */
    public function addCurrency(string $code)
    {
        /** @var Currency $currency */
        $currency = new $this->currencyClassName();
        $currency->setCode($code);

        $this->em->persist($currency);
        $this->em->flush();
    }

}
