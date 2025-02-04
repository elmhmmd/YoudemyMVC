<?php

    function validateInput(string $input): string
    {
        $input = filter_var($input, FILTER_SANITIZE_SPECIAL_CHARS);
        $input = trim($input);
        $input = htmlspecialchars($input);

        return $input;
    }
