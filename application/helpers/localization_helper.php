<?php

function lang(string $line, array $bindings = []): string
{
    global $_ci;
    $line = $_ci->lang->line($line);
    if (!empty($bindings)) {
        $replace = [];
        foreach ($bindings as $key => $value) {
            $replace['{' . $key . '}'] = $value;
        }
        $line = strtr($line, $replace);
    }
    return $line;
}

function loadLanguage(): void
{
    global $_ci;
    $language = !empty(setup("languages")) ? detectLanguage() : "english";
    $_ci->config->set_item('language', $language);
    $_ci->lang->load('app', $language);
}

function detectLanguage(): string
{
    global $_ci;
    $cookie = get_cookie("language");
    $languages = setup("languages");

    if ($cookie && !empty($languages[$cookie]))
        return $cookie;

    $sets = !$_ci->agent->is_robot() ? $_ci->sets->get("language.user") : $_ci->sets->get("language.robot");
    if (!empty($languages[$sets]))
        return $sets;

    $browserLanguages = $_ci->agent->languages();
    foreach ($languages as $key => $value) {
        $slugs[$value["slug"]] = $key;
    }
    foreach ($browserLanguages as $browserLanguage) {
        if (!empty($slugs[$browserLanguage]))
            return $slugs[$browserLanguage];
    }

    return key(setup("languages"));
}

function language(bool $full = false): string
{
    global $_ci;
    $language = $_ci->config->item("language");
    if (!$full)
        return setup("languages")[$language]["slug"] ?? "en";
    else
        return $language;

}

loadLanguage();