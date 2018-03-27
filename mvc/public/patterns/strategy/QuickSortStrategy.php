<?php
class QuickSortStrategy implements SortStrategy {
    public function sort(array $dataset) : array {
        echo "Sorting using quick sort";

        // Do sorting
        return $dataset;
    }
}