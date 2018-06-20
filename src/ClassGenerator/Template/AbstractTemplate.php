<?php namespace DCarbone\PHPFHIR\ClassGenerator\Template;

/*
 * Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * TODO: Restructure template system so documentation is not implemented where applicable.
 *
 * Class AbstractTemplate
 * @package DCarbone\PHPFHIR\ClassGenerator\Template
 */
abstract class AbstractTemplate {
    /** @var array */
    protected $documentation = array();

    /**
     * @return array
     */
    public function getDocumentation() {
        return $this->documentation;
    }

    /**
     * @param string|array|null $documentation
     */
    public function setDocumentation($documentation) {
        if (null !== $documentation) {
            if (is_string($documentation)) {
                $documentation = array($documentation);
            }

            if (is_array($documentation)) {
                $this->documentation = $documentation;
            } else {
                throw new \InvalidArgumentException('Documentation expected to be array, string, or null.');
            }
        }
    }

    /**
     * @return string
     */
    abstract public function compileTemplate();

    /**
     * @see compileTemplate
     * @return string By default, returns output of compileTemplate
     */
    public function __toString() {
        return $this->compileTemplate();
    }

    /**
     * @param int $spaces
     * @param int $maxWidth
     * @param int $tabSize
     * @return string
     */
    protected function getDocBlockDocumentationFragment($spaces = 5, $maxWidth = 120, $tabSize = 4) {
        if (empty($this->documentation)) {
            return '';
        }

        $output = '';
        $spacing = str_repeat(' ', $spaces);
        foreach ($this->documentation as $doc) {
            $doc = rtrim(str_replace(["\r\n", "\t"], ["\n", $tabSize], $doc));
            $doc = wordwrap($doc, $maxWidth - $spaces - 2); // 2 = strlen('* ')
            foreach (explode("\n", $doc) as $line) {
                $output .= sprintf("%s* %s\n", $spacing, rtrim($line));
            }
        }

        return $output;
    }

    protected function trimTrailingSpaces($string)
    {
        return implode("\n", array_map('rtrim', explode("\n", $string)));
    }
}
