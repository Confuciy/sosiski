<?php
class BubbleSortStrategy implements SortStrategy {
    public function sort(array $dataset) : array {
        echo "Sorting using bubble sort";

        // Do sorting
        return $dataset;
    }
}