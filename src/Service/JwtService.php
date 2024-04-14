<?php

namespace SamuelPouzet\Api\Service;

use DateTimeImmutable;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
// use Lcobucci\JWT\UnencryptedToken;

class JwtService
{

    protected $builder;
    protected $creation;

    public function __construct(
        protected array $config
    )
    {
    }


    public function build(): self
    {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $this->builder = $tokenBuilder;
        return $this;
    }

    public function addClaim(string $title, string|int $value): self
    {
        if (!$this->builder) {
            $this->build();
        }
        $this->builder->withClaim($title, $value);
        return $this;
    }

    public function setExpiration(\DateInterval $interval): self
    {
        if (!$this->builder) {
            $this->build();
        }
        $now = new DateTimeImmutable();
        $this->builder
            ->issuedAt($now)
            ->expiresAt($now->add($interval));
        return $this;
    }

    public function generate(): string
    {
        if (!$this->builder) {
            $this->build();
        }
        $algorithm    = new Sha256();
        $signingKey   = InMemory::plainText(random_bytes(32));
        return $this->builder
            ->getToken($algorithm, $signingKey)
            ->toString();
    }

    public function parse(string $token): Plain
    {
        $parser = new Parser(new JoseEncoder());

        try {
            $token = $parser->parse($token);
            assert($token instanceof Plain);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            echo 'Oh no, an error: ' . $e->getMessage();
        }
        return $token;
    }

}