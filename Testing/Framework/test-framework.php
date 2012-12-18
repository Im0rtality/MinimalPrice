<?php
// ==================================================================
//
// Test framework
//
// ------------------------------------------------------------------
    class TestFramework {
    	private $total = 0;
    	private $success = 0;
        private $tests = array();
        private $haltOnFail;

        function __construct($haltOnFail = false) {
            $this->haltOnFail = $haltOnFail;
        }

    	function Assert($success, $testname, $message = 0) {
    		$this->total++;
            $this->tests[] = array("name" => $testname, "result" => $success, "message" => $message);
            if ($this->haltOnFail) {
                die("Test {$testname} FAILED.");
            }
    	}

        function getResults($format = "html") {
            if ($this->total == 0) {
                return "No tests performed";
            }
            $failed = $this->total - $this->success;
            $output = "";
            switch (strtolower($format)) {
                case "html":
                default:
                    foreach($this->tests as $test) {
                        if ($test['result'] === true) {
                            $output .= "[\"{$test['name']}\"]: <font color='green'>OK</font><br/>";
                            $this->success++;
                        } else {
                            $output .= "[\"{$test['name']}\"]: <font color='red'>FAILED</font> ({$test['message']})<br/>";
                        }
                    }
                    $output .= "<hr/>";
                    $output .= "<strong>Statistics:</strong><br/>";
                    $output .= "Total tests: <strong>{$this->total}</strong><br/>";
                    $output .= "Succeeded tests: <strong>{$this->success} (" . round($this->success / $this->total * 100). "%)</strong><br/>";
                    $output .= "Failed tests: <strong>{$failed} (" . round($failed / $this->total * 100). "%)</strong><br/>";
                    break;
            }
            return $output;
        }
    }

?>