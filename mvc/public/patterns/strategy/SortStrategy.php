<?php
interface SortStrategy {
    public function sort(array $dataset) : array;
}