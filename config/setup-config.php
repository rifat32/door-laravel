<?php

return [
    "roles_permission" => [
        [
            "role" => "admin",
            "permissions" => [
                "approve requisition",
                "cancel requisition",
                "create purchase",
                "purchase return",
                "fixed asset stock",
                "category wise stock",
                "create stockout request",
                "approve stockout request",
                "deney stockout request",
                "voucher approval",
                "add invoice",
                "add revenue",
                "approve revenue",
                "add debit voucher",
                "approve voucher",
                "approve payment",

                "approve fund request",
                "cancel fund request",
                "add fund",
                "transfer fund",
            ],
        ],
        [
            "role" => "parchase officer",
            "permissions" => [
                "create requisition",
                "create purchase",
                "purchase return",
            ],
        ],
        [
            "role" => "stock manager",
            "permissions" => [
                "check stock",
                "create stockout request",
                "approve stockout request",
                "add stockout",
            ],

        ],
        [
            "role" => "accounts manager",
            "permissions" => [
                "add credit voucher",
                "add revenue",
                "approve revenue",
                "add payment",
            ],

        ],
        [
            "role" => "wing manager",
            "permissions" => [
                "apply for fund request",
            ],

        ],

    ],
    "roles" => [
        "admin",
        "parchase officer",
        "stock manager",
        "accounts manager",
        "wing manager"
    ],
    "permissions" => [
        // parchase
        "create requisition",
        "approve requisition",
        "cancel requisition",
        "create purchase",
        "purchase return",
        // inventory
        "check stock",
        "fixed asset stock",
        "category wise stock",
        "create stockout request",
        "approve stockout request",
        "deney stockout request",
        "add stockout",
        // account management
        "add credit voucher",
        "voucher approval",
        "add invoice",
        "add revenue",
        "approve revenue",
        "add debit voucher",
        "approve voucher",
        "add payment",
        "approve payment",
        // Fund Transfer
        "apply for fund request",
        "approve fund request",
        "cancel fund request",
        "add fund",
        "transfer fund",



    ],

];
