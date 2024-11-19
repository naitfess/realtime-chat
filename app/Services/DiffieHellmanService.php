<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DiffieHellmanService
{
    /**
     * Fungsi untuk menghitung public key dari private key.
     * 
     * @param int $privateKey
     * @param int $p
     * @param int $g
     * @return int
     */
    public function generatePublicKey(int $privateKey, int $p, int $g)
    {
        // A = G^a % P
        // return gmp_strval(gmp_powm($g, $privateKey, $p)); 
        // return $g ** $privateKey % $p;
        return bcpowmod(strval($g), strval($privateKey), strval($p));  // Eksponensiasi modular dengan bcpowmod

    }

    /**
     * Fungsi untuk menghitung shared secret berdasarkan kunci publik pihak lain.
     * 
     * @param int $publicKey
     * @param int $privateKey
     * @param int $p
     * @return int
     */
    public function generateSharedSecret(int $publicKey, int $privateKey, int $p)
    {
        // Shared Secret = B^a % P
        // return gmp_strval(gmp_powm($publicKey, $privateKey, $p)); 
        // return $publicKey ** $privateKey % $p;
        return bcpowmod(strval($publicKey), strval($privateKey), strval($p));  // Menghitung shared secret dengan bcpowmod
    }
}
