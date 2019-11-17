<?php

interface I_MarkupParser {
    public function setConfig(array $config);
    public function parse($text);
}
