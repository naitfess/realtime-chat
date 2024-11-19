<?php

namespace App\Services;

class EncryptionService
{
    /**
     * Enkripsi dengan Caesar Cipher
     * @param string $text Teks yang akan dienkripsi
     * @param int $shift Jumlah pergeseran (positif untuk enkripsi)
     * @return string Teks yang terenkripsi
     */
    public function caesarEncrypt($text, $shift)
    {
        $result = '';

        foreach (str_split($text) as $char) {
            if (ctype_alpha($char)) {  // Memeriksa apakah karakter alfabet
                $asciiOffset = ctype_upper($char) ? 65 : 97;  // Menentukan offset untuk huruf besar atau kecil
                // Menggunakan ord() untuk mendapatkan nilai ASCII dari karakter
                $result .= chr((ord($char) - $asciiOffset + $shift) % 26 + $asciiOffset);
            } else {
                // Karakter selain huruf tetap tidak berubah
                $result .= $char;
            }
        }

        return $result;
    }

    /**
     * Dekripsi dengan Caesar Cipher
     * @param string $text Teks yang akan didekripsi
     * @param int $shift Jumlah pergeseran (negatif untuk dekripsi)
     * @return string Teks yang didekripsi
     */
    public function caesarDecrypt(string $text, int $shift): string
    {
        // Untuk dekripsi, cukup membalikkan pergeseran dengan angka negatif
        return $this->caesarEncrypt($text, -$shift);  // Memanggil caesarEncrypt untuk dekripsi
    }

    /**
     * Enkripsi dan Dekripsi menggunakan RC4
     * @param string $data Data yang akan dienkripsi atau didekripsi
     * @param string $key Kunci untuk enkripsi/dekripsi
     * @return string Data yang terenkripsi atau terdekripsi
     */
    public function rc4Encrypt(string $data, string $key): string
    {
        // Inisialisasi array state (S)
        $s = range(0, 255);
        $j = 0;
        $keyLength = strlen($key);

        // Key Scheduling Algorithm (KSA)
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % $keyLength])) % 256;
            // Tukar nilai dalam array state
            $temp = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $temp;
        }

        // Pseudo-Random Generation Algorithm (PRGA)
        $i = 0;
        $j = 0;
        $output = '';
        for ($k = 0; $k < strlen($data); $k++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;

            // Tukar nilai dalam array state
            $temp = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $temp;

            // Generate keystream dan XOR dengan data
            $output .= chr(ord($data[$k]) ^ $s[($s[$i] + $s[$j]) % 256]);
        }

        // Base64 encode hasil enkripsi agar aman disimpan
        return base64_encode($output);
    }

    public function rc4Decrypt(string $data, string $key): string
    {
        // Decode base64 terlebih dahulu untuk mendapatkan data asli yang terenkripsi
        $decodedData = base64_decode($data);

        // Inisialisasi array state (S)
        $s = range(0, 255);
        $j = 0;
        $keyLength = strlen($key);

        // Key Scheduling Algorithm (KSA)
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % $keyLength])) % 256;
            // Tukar nilai dalam array state
            $temp = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $temp;
        }

        // Pseudo-Random Generation Algorithm (PRGA)
        $i = 0;
        $j = 0;
        $output = '';
        for ($k = 0; $k < strlen($decodedData); $k++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;

            // Tukar nilai dalam array state
            $temp = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $temp;

            // Generate keystream dan XOR dengan data untuk mendekripsi
            $output .= chr(ord($decodedData[$k]) ^ $s[($s[$i] + $s[$j]) % 256]);
        }

        return $output;  // Mengembalikan hasil dekripsi
    }
}
