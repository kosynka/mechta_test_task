<?php

namespace VBulletin\Render;

// Этот класс нужно сильно переделать, либо отказаться от $render объекта и использовать echo вместо него
// либо переделать методы класса Renderer, чтобы они принимали данные и отриосывали их сами
// либо использовать шаблонизатор для вывода данных
class Render
{
    private object $render;

    public static function renderSearchResults(array $result)
    {
        foreach ($result as $row) {
            if ($row['forumid'] !== 5) {
                self::renderSearchResult($row);
            }
        }
    }

    public static function renderSearchResult(string $row)
    {
        self::$render->render_searh_result($row);
    }

    public static function renderSearchForm()
    {
        echo "<h2>Search in forum</h2><form><input name='q'></form>";
    }
}
