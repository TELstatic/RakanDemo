<?php

namespace App\Console\Commands\ElasticSearch\Indices;

class ChineseArticleIndex
{
    public static function getAliasName()
    {
        return 'ch_articles';
    }

    public static function getProperties()
    {
        return [
            'title'             => [
                'type'     => 'text',
                'analyzer' => 'ngram_analyzer'
            ],
            'is_simple'         => [
                'type' => 'boolean'
            ],
            'notice_start'      => [
                'type' => 'keyword'
            ],
            'notice_end'        => [
                'type' => 'keyword'
            ],
            'ratification'      => [
                'type' => 'integer'
            ],
            'closed_at'         => [
                'type' => 'keyword'
            ],
            'market'            => [
                'type'     => 'text',
                'analyzer' => 'ngram_analyzer'
            ],
            'actor'             => [
                'type'     => 'text',
                'analyzer' => 'ngram_analyzer'
            ],
            'publicity'         => [
                'type' => 'integer'
            ],
            'content'           => [
                'type'     => 'text',
                'analyzer' => 'ngram_analyzer'
            ],
            'notice_start_time' => [
                'type' => 'integer'
            ],
            'closed_at_time'    => [
                'type' => 'integer'
            ]
        ];
    }

    public static function getSettings()
    {
        return [
            "analysis" => [
                "analyzer"  => [
                    "ngram_analyzer" => [
                        "tokenizer" => "ngram_tokenizer"
                    ]
                ],
                "tokenizer" => [
                    "ngram_tokenizer" => [
                        "type"        => "ngram",
                        "min_gram"    => 1,
                        "max_gram"    => 30,
                        "token_chars" => [
                            "letter",
                            "digit"
                        ]
                    ]
                ]
            ]
        ];
    }
}