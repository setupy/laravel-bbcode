<?php

namespace Setupy\BBCode;


class BBCodeParser
{
    /**
     * All bbcodes.
     *
     * @var array
     */
    public $bbcodes =[];

    /**
     * Enabled bbcodes for parse.
     *
     * @var array
     */
    private $enabledBBCodes;

    /**
     * BBCodeParser constructor.
     *
     * @param array $bbcodes
     */
    public function __construct(array $bbcodes)
    {
        $this->enabledBBCodes = $this->bbcodes = $bbcodes;
    }

    /**
     * Parses the BBCode string
     *
     * @param      $source
     * @param bool $caseInsensitive
     * @return string
     */
    public function parse($source, $caseInsensitive = false)
    {
        foreach ($this->enabledBBCodes as $name => $parser) {
            $pattern = ($caseInsensitive) ? $parser['pattern'] . 'i' : $parser['pattern'];

            $source = $this->searchAndReplace($pattern, $parser['replace'], $source);
        }

        return $source;
    }

    /**
     * Remove all BBCode
     *
     * @param  string $source
     * @return string Parsed text
     */
    public function stripBBCodeTags($source)
    {
        foreach ($this->bbcodes as $name => $parser) {
            $source = $this->searchAndReplace($parser['pattern'] . 'i', $parser['content'], $source);
        }

        return $source;
    }

    /**
     * Searches after a specified pattern and replaces it with provided structure
     *
     * @param  string $pattern Search pattern
     * @param  string $replace Replacement structure
     * @param  string $source  Text to search in
     * @return string Parsed text
     */
    protected function searchAndReplace($pattern, $replace, $source)
    {
        while (preg_match($pattern, $source)) {
            $source = preg_replace($pattern, $replace, $source);
        }

        return $source;
    }

    /**
     * Helper function to parse case sensitive
     *
     * @param  string $source String containing the BBCode
     * @return string Parsed text
     */
    public function parseCaseSensitive($source)
    {
        return $this->parse($source, false);
    }

    /**
     * Helper function to parse case insensitive
     *
     * @param  string $source String containing the BBCode
     * @return string Parsed text
     */
    public function parseCaseInsensitive($source)
    {
        return $this->parse($source, true);
    }

    /**
     * Limits the parsers to only those you specify
     *
     * @param  mixed $only parsers
     * @return object BBCodeParser object
     */
    public function only($only = null)
    {
        $only = (is_array($only)) ? $only : func_get_args();
        $this->enabledBBCodes = array_intersect_key($this->bbcodes, array_flip((array) $only));

        return $this;
    }

    /**
     * Removes the parsers you want to exclude
     *
     * @param  mixed $except parsers
     * @return object BBCodeParser object
     */
    public function except($except = null)
    {
        $except = (is_array($except)) ? $except : func_get_args();
        $this->enabledBBCodes = array_diff_key($this->bbcodes, array_flip((array) $except));

        return $this;
    }

    /**
     * List of chosen parsers
     *
     * @return array array of parsers
     */
    public function getBBCodes()
    {
        return $this->enabledBBCodes;
    }

    /**
     * Sets the parser pattern and replace.
     * This can be used for new parsers or overwriting existing ones.
     *
     * @param string $name    bbcode name
     * @param string $pattern Pattern
     * @param string $replace Replace pattern
     * @param string $content Parsed text pattern
     * @return void
     */
    public function setBBCode($name, $pattern, $replace, $content)
    {
        $this->bbcodes[$name] =compact('pattern', 'replace', 'content');
        $this->enabledBBCodes[$name] = $this->bbcodes[$name];
    }
}
