<?php

namespace SamuelPouzet\Api\Service;

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Signer\Key\InMemory;

class JwtService
{

    public function __construct(
        protected array $config
    ) {
        var_dump($this->config);
    }

    public function validate(string $token): bool
    {
        $parser = new Parser(new JoseEncoder());

        $token = $parser->parse($token);

        $validator = new Validator();

        return $validator->validate($token, new RelatedTo($this->config['bearer']));

    }

    public function encode(string $token): bool
    {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm    = new Sha256();
        $signingKey   = InMemory::plainText(random_bytes(32));

        $now   = new DateTimeImmutable();
        $token = $tokenBuilder
            // Configures the issuer (iss claim)
            ->issuedBy('http://example.com')
            // Configures the audience (aud claim)
            ->permittedFor('http://example.org')
            // Configures the subject of the token (sub claim)
            ->relatedTo('component1')
            // Configures the id (jti claim)
            ->identifiedBy('4f1g23a12aa')
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+1 hour'))
            // Configures a new claim, called "uid"
            ->withClaim('uid', 1)
            // Configures a new header, called "foo"
            ->withHeader('foo', 'bar')
            // Builds a new token
            ->getToken($algorithm, $signingKey);

        echo $token->toString();

    }


}