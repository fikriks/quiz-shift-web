<?php

namespace App\Libraries;

/**
 * FisherYates Shuffle Library
 *
 * Implements the Fisher-Yates shuffle algorithm for randomizing arrays.
 * This algorithm provides an unbiased permutation with O(n) time complexity.
 */
class FisherYates
{
    /**
     * Shuffle array using Fisher-Yates algorithm
     *
     * @param array $array The array to shuffle
     * @return array The shuffled array
     */
    public function shuffle(array $array): array
    {
        $shuffled = $array;
        $count = count($shuffled);

        for ($i = $count - 1; $i > 0; $i--) {
            $j = rand(0, $i);
            // Swap elements
            $temp = $shuffled[$i];
            $shuffled[$i] = $shuffled[$j];
            $shuffled[$j] = $temp;
        }

        return $shuffled;
    }

    /**
     * Shuffle array and preserve keys
     *
     * @param array $array The associative array to shuffle
     * @return array The shuffled array with keys preserved
     */
    public function shuffleWithKeys(array $array): array
    {
        $keys = array_keys($array);
        $shuffledKeys = $this->shuffle($keys);
        $result = [];

        foreach ($shuffledKeys as $key) {
            $result[$key] = $array[$key];
        }

        return $result;
    }

    /**
     * Shuffle array and return with sequential indices starting from 1
     * Useful for quiz questions where order matters
     *
     * @param array $array The array to shuffle
     * @return array The shuffled array with new sequential keys
     */
    public function shuffleWithOrder(array $array): array
    {
        $shuffled = $this->shuffle($array);
        $result = [];

        $order = 1;
        foreach ($shuffled as $item) {
            // Add urutan field to each item
            if (is_array($item)) {
                $item['urutan_soal'] = $order;
            }
            $result[] = $item;
            $order++;
        }

        return $result;
    }

    /**
     * Pick random elements from array
     *
     * @param array $array The source array
     * @param int $count Number of elements to pick
     * @return array Array with random elements
     */
    public function pickRandom(array $array, int $count): array
    {
        if ($count >= count($array)) {
            return $this->shuffle($array);
        }

        $shuffled = $this->shuffle($array);
        return array_slice($shuffled, 0, $count);
    }

    /**
     * Shuffle and reorder array elements with numeric keys
     *
     * @param array $array The array to shuffle
     * @return array The shuffled array with reset numeric keys
     */
    public function shuffleAndReindex(array $array): array
    {
        $shuffled = $this->shuffle($array);
        return array_values($shuffled);
    }
}
