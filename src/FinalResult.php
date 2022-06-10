<?php

class FinalResult {
    function results($f) {
        $d = fopen($f, "r");
        $h = fgetcsv($d);
        $rcs = [];
        while(!feof($d)) {
            $r = fgetcsv($d);
            /* CODE CHANGE START */
            if(count($r) == 7) {
                $amt = !$r[4] || $r[4] == "0" ? 0 : (float) $r[4];
                $ban = !$r[2] ? "Bank account number missing" : (int) $r[2];
                $bac = !$r[1] ? "Bank branch code missing" : $r[1];
                $e2e = !$r[5] && !$r[6] ? "End to end id missing" : $r[5] . $r[6];
                $rcd = [
                    "amount" => [
                        "currency" => $h[0],
                        "subunits" => (int) ($amt * 100)
                    ],
                    "bank_account_name" => str_replace(" ", "_", strtolower($r[3])),
                    "bank_account_number" => $ban,
                    "bank_branch_code" => $bac,
                    "bank_code" => $r[0],
                    "end_to_end_id" => $e2e,
                ];
                $rcs[] = $rcd;
            }
            /*CODE CHANGE END*/
        }
        $rcs = array_filter($rcs);
        return [
            "filename" => basename($f),
            "document" => $d,
            "failure_code" => $h[1],
            "failure_message" => $h[2],
            "records" => $rcs
        ];
    }
}

?>
