<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SurveyResponses;

class responsesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SurveyResponses::create([
            'user_id' => 7,
            'first_name' => 'Rizki',
            'surname' => 'Hidayat',
            'email' => 'k9qFP@example.com',
            'birth_date' => '2002-05-17',
            'gender' => 'Male',
            'profession' => 'Student',
            'educational_background' => "Bachelor's Degree",
            'response_data' => '{
                                    "sus": {
                                        "sus1": 1,
                                        "sus2": 4,
                                        "sus3": 2,
                                        "sus4": 4,
                                        "sus5": 1,
                                        "sus6": 5,
                                        "sus7": 1,
                                        "sus8": 5,
                                        "sus9": 2,
                                        "sus10": 4
                                    },
                                    "tam": [
                                        {
                                        "name": "PEU",
                                        "responses": [
                                            {
                                            "name": "Kemudahan dipelajari",
                                            "value": [
                                                [
                                                "tam1",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah dipahami/dimengerti",
                                            "value": [
                                                [
                                                "tam2",
                                                2
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah sehingga mahir",
                                            "value": [
                                                [
                                                "tam3",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah digunakan",
                                            "value": [
                                                [
                                                "tam4",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah dikendalikan",
                                            "value": [
                                                [
                                                "tam5",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah diingat",
                                            "value": [
                                                [
                                                "tam6",
                                                4
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "PU",
                                        "responses": [
                                            {
                                            "name": "Lebih cepat",
                                            "value": [
                                                [
                                                "tam7",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan kinerja",
                                            "value": [
                                                [
                                                "tam8",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan produktivitas",
                                            "value": [
                                                [
                                                "tam9",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan efektivitas",
                                            "value": [
                                                [
                                                "tam10",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Lebih mudah",
                                            "value": [
                                                [
                                                "tam11",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Bermanfaat",
                                            "value": [
                                                [
                                                "tam12",
                                                5
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "ATU",
                                        "responses": [
                                            {
                                            "name": "Rasa senang",
                                            "value": [
                                                [
                                                "tam13",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menikmati",
                                            "value": [
                                                [
                                                "tam14",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Rasa bosan",
                                            "value": [
                                                [
                                                "tam15",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Tidak suka",
                                            "value": [
                                                [
                                                "tam16",
                                                2
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "BI",
                                        "responses": [
                                            {
                                            "name": "Menggunakan kapan saja",
                                            "value": [
                                                [
                                                "tam17",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menggunakan kondisi apapun",
                                            "value": [
                                                [
                                                "tam18",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Niat menggunakan terus",
                                            "value": [
                                                [
                                                "tam19",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Berharap menggunakan",
                                            "value": [
                                                [
                                                "tam20",
                                                5
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "ASU",
                                        "responses": [
                                            {
                                            "name": "Frekuensi penggunaan",
                                            "value": [
                                                [
                                                "tam21",
                                                3
                                                ],
                                                [
                                                "tam22",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Durasi penggunaan",
                                            "value": [
                                                [
                                                "tam23",
                                                4
                                                ]
                                            ]
                                            }
                                        ]
                                        }
                                    ]
                                }',
            'survey_id' => 1
        ]);

        SurveyResponses::create([
            'user_id' => 2,
            'first_name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'jane.doe@example.com',
            'birth_date' => '1990-12-31',
            'gender' => 'Female',
            'profession' => 'Other',
            'educational_background' => "Master's Degree",
            'response_data' => '{
                                    "sus": {
                                        "sus1": 5,
                                        "sus2": 1,
                                        "sus3": 5,
                                        "sus4": 1,
                                        "sus5": 5,
                                        "sus6": 1,
                                        "sus7": 5,
                                        "sus8": 1,
                                        "sus9": 5,
                                        "sus10": 1
                                    },
                                    "tam": [
                                        {
                                        "name": "PEU",
                                        "responses": [
                                            {
                                            "name": "Kemudahan dipelajari",
                                            "value": [
                                                [
                                                "tam1",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah dipahami/dimengerti",
                                            "value": [
                                                [
                                                "tam2",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah sehingga mahir",
                                            "value": [
                                                [
                                                "tam3",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah digunakan",
                                            "value": [
                                                [
                                                "tam4",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah dikendalikan",
                                            "value": [
                                                [
                                                "tam5",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah diingat",
                                            "value": [
                                                [
                                                "tam6",
                                                4
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "PU",
                                        "responses": [
                                            {
                                            "name": "Lebih cepat",
                                            "value": [
                                                [
                                                "tam7",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan kinerja",
                                            "value": [
                                                [
                                                "tam8",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan produktivitas",
                                            "value": [
                                                [
                                                "tam9",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan efektivitas",
                                            "value": [
                                                [
                                                "tam10",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Lebih mudah",
                                            "value": [
                                                [
                                                "tam11",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Bermanfaat",
                                            "value": [
                                                [
                                                "tam12",
                                                4
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "ATU",
                                        "responses": [
                                            {
                                            "name": "Rasa senang",
                                            "value": [
                                                [
                                                "tam13",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menikmati",
                                            "value": [
                                                [
                                                "tam14",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Rasa bosan",
                                            "value": [
                                                [
                                                "tam15",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Tidak suka",
                                            "value": [
                                                [
                                                "tam16",
                                                3
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "BI",
                                        "responses": [
                                            {
                                            "name": "Menggunakan kapan saja",
                                            "value": [
                                                [
                                                "tam17",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menggunakan kondisi apapun",
                                            "value": [
                                                [
                                                "tam18",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Niat menggunakan terus",
                                            "value": [
                                                [
                                                "tam19",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Berharap menggunakan",
                                            "value": [
                                                [
                                                "tam20",
                                                4
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "ASU",
                                        "responses": [
                                            {
                                            "name": "Frekuensi penggunaan",
                                            "value": [
                                                [
                                                "tam21",
                                                5
                                                ],
                                                [
                                                "tam22",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Durasi penggunaan",
                                            "value": [
                                                [
                                                "tam23",
                                                5
                                                ]
                                            ]
                                            }
                                        ]
                                        }
                                    ]
                                }',
            'survey_id' => 1
        ]);

        SurveyResponses::create([
            'user_id' => 3,
            'first_name' => 'Emily',
            'surname' => 'Johnson',
            'email' => 'emily.johnson@example.com',
            'birth_date' => '1998-03-20',
            'gender' => 'Female',
            'profession' => 'Private Worker',
            'educational_background' => "High School",
            'response_data' => '{
                                    "sus": {
                                        "sus1": 4,
                                        "sus2": 3,
                                        "sus3": 3,
                                        "sus4": 4,
                                        "sus5": 3,
                                        "sus6": 2,
                                        "sus7": 3,
                                        "sus8": 5,
                                        "sus9": 2,
                                        "sus10": 1
                                    },
                                    "tam": [
                                        {
                                        "name": "PEU",
                                        "responses": [
                                            {
                                            "name": "Kemudahan dipelajari",
                                            "value": [
                                                [
                                                "tam1",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah dipahami/dimengerti",
                                            "value": [
                                                [
                                                "tam2",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah sehingga mahir",
                                            "value": [
                                                [
                                                "tam3",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah digunakan",
                                            "value": [
                                                [
                                                "tam4",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah dikendalikan",
                                            "value": [
                                                [
                                                "tam5",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah diingat",
                                            "value": [
                                                [
                                                "tam6",
                                                4
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "PU",
                                        "responses": [
                                            {
                                            "name": "Lebih cepat",
                                            "value": [
                                                [
                                                "tam7",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan kinerja",
                                            "value": [
                                                [
                                                "tam8",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan produktivitas",
                                            "value": [
                                                [
                                                "tam9",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan efektivitas",
                                            "value": [
                                                [
                                                "tam10",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Lebih mudah",
                                            "value": [
                                                [
                                                "tam11",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Bermanfaat",
                                            "value": [
                                                [
                                                "tam12",
                                                5
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "ATU",
                                        "responses": [
                                            {
                                            "name": "Rasa senang",
                                            "value": [
                                                [
                                                "tam13",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menikmati",
                                            "value": [
                                                [
                                                "tam14",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Rasa bosan",
                                            "value": [
                                                [
                                                "tam15",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Tidak suka",
                                            "value": [
                                                [
                                                "tam16",
                                                5
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "BI",
                                        "responses": [
                                            {
                                            "name": "Menggunakan kapan saja",
                                            "value": [
                                                [
                                                "tam17",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menggunakan kondisi apapun",
                                            "value": [
                                                [
                                                "tam18",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Niat menggunakan terus",
                                            "value": [
                                                [
                                                "tam19",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Berharap menggunakan",
                                            "value": [
                                                [
                                                "tam20",
                                                3
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "ASU",
                                        "responses": [
                                            {
                                            "name": "Frekuensi penggunaan",
                                            "value": [
                                                [
                                                "tam21",
                                                4
                                                ],
                                                [
                                                "tam22",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Durasi penggunaan",
                                            "value": [
                                                [
                                                "tam23",
                                                5
                                                ]
                                            ]
                                            }
                                        ]
                                        }
                                    ]
                                    }',
            'survey_id' => 1
        ]);

        SurveyResponses::create([
            'user_id' => 4,
            'first_name' => 'Michael',
            'surname' => 'Brown',
            'email' => 'michael.brown@example.com',
            'birth_date' => '1995-07-10',
            'gender' => 'Male',
            'profession' => 'Professor',
            'educational_background' => "Doctorate Degree",
            'response_data' => '{
                                    "sus": {
                                        "sus1": 5,
                                        "sus2": 1,
                                        "sus3": 5,
                                        "sus4": 1,
                                        "sus5": 5,
                                        "sus6": 1,
                                        "sus7": 5,
                                        "sus8": 1,
                                        "sus9": 5,
                                        "sus10": 1
                                    },
                                    "tam": [
                                        {
                                        "name": "PEU",
                                        "responses": [
                                            {
                                            "name": "Kemudahan dipelajari",
                                            "value": [
                                                [
                                                "tam1",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah dipahami/dimengerti",
                                            "value": [
                                                [
                                                "tam2",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah sehingga mahir",
                                            "value": [
                                                [
                                                "tam3",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah digunakan",
                                            "value": [
                                                [
                                                "tam4",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah dikendalikan",
                                            "value": [
                                                [
                                                "tam5",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Mudah diingat",
                                            "value": [
                                                [
                                                "tam6",
                                                4
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "PU",
                                        "responses": [
                                            {
                                            "name": "Lebih cepat",
                                            "value": [
                                                [
                                                "tam7",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan kinerja",
                                            "value": [
                                                [
                                                "tam8",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan produktivitas",
                                            "value": [
                                                [
                                                "tam9",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Meningkatkan efektivitas",
                                            "value": [
                                                [
                                                "tam10",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Lebih mudah",
                                            "value": [
                                                [
                                                "tam11",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Bermanfaat",
                                            "value": [
                                                [
                                                "tam12",
                                                3
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "ATU",
                                        "responses": [
                                            {
                                            "name": "Rasa senang",
                                            "value": [
                                                [
                                                "tam13",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menikmati",
                                            "value": [
                                                [
                                                "tam14",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Rasa bosan",
                                            "value": [
                                                [
                                                "tam15",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Tidak suka",
                                            "value": [
                                                [
                                                "tam16",
                                                4
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "BI",
                                        "responses": [
                                            {
                                            "name": "Menggunakan kapan saja",
                                            "value": [
                                                [
                                                "tam17",
                                                5
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menggunakan kondisi apapun",
                                            "value": [
                                                [
                                                "tam18",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Niat menggunakan terus",
                                            "value": [
                                                [
                                                "tam19",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Berharap menggunakan",
                                            "value": [
                                                [
                                                "tam20",
                                                4
                                                ]
                                            ]
                                            }
                                        ]
                                        },
                                        {
                                        "name": "ASU",
                                        "responses": [
                                            {
                                            "name": "Frekuensi penggunaan",
                                            "value": [
                                                [
                                                "tam21",
                                                5
                                                ],
                                                [
                                                "tam22",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Durasi penggunaan",
                                            "value": [
                                                [
                                                "tam23",
                                                3
                                                ]
                                            ]
                                            }
                                        ]
                                        }
                                    ]
                                }',
            'survey_id' => 1
        ]);

        SurveyResponses::create([
            'user_id' => 5,
            'first_name' => 'David',
            'surname' => 'Lee',
            'email' => 'david.lee@example.com',
            'birth_date' => '1988-04-02',
            'gender' => 'Male',
            'profession' => 'Armed Forces',
            'educational_background' => "Associate's Degree",
            'response_data' => '{
                                "sus": {
                                    "sus1": 3,
                                    "sus2": 1,
                                    "sus3": 4,
                                    "sus4": 2,
                                    "sus5": 5,
                                    "sus6": 1,
                                    "sus7": 2,
                                    "sus8": 4,
                                    "sus9": 2,
                                    "sus10": 5
                                },
                                "tam": [
                                    {
                                    "name": "PEU",
                                    "responses": [
                                        {
                                        "name": "Kemudahan dipelajari",
                                        "value": [
                                            [
                                            "tam1",
                                            4
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Mudah dipahami/dimengerti",
                                        "value": [
                                            [
                                            "tam2",
                                            3
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Mudah sehingga mahir",
                                        "value": [
                                            [
                                            "tam3",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Mudah digunakan",
                                        "value": [
                                            [
                                            "tam4",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Mudah dikendalikan",
                                        "value": [
                                            [
                                            "tam5",
                                            2
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Mudah diingat",
                                        "value": [
                                            [
                                            "tam6",
                                            5
                                            ]
                                        ]
                                        }
                                    ]
                                    },
                                    {
                                    "name": "PU",
                                    "responses": [
                                        {
                                        "name": "Lebih cepat",
                                        "value": [
                                            [
                                            "tam7",
                                            4
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Meningkatkan kinerja",
                                        "value": [
                                            [
                                            "tam8",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Meningkatkan produktivitas",
                                        "value": [
                                            [
                                            "tam9",
                                            3
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Meningkatkan efektivitas",
                                        "value": [
                                            [
                                            "tam10",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Lebih mudah",
                                        "value": [
                                            [
                                            "tam11",
                                            4
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Bermanfaat",
                                        "value": [
                                            [
                                            "tam12",
                                            4
                                            ]
                                        ]
                                        }
                                    ]
                                    },
                                    {
                                    "name": "ATU",
                                    "responses": [
                                        {
                                        "name": "Rasa senang",
                                        "value": [
                                            [
                                            "tam13",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Menikmati",
                                        "value": [
                                            [
                                            "tam14",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Rasa bosan",
                                        "value": [
                                            [
                                            "tam15",
                                            4
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Tidak suka",
                                        "value": [
                                            [
                                            "tam16",
                                            1
                                            ]
                                        ]
                                        }
                                    ]
                                    },
                                    {
                                    "name": "BI",
                                        "responses": [
                                            {
                                            "name": "Menggunakan kapan saja",
                                            "value": [
                                                [
                                                "tam17",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menggunakan kondisi apapun",
                                            "value": [
                                                [
                                                "tam18",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Niat menggunakan terus",
                                            "value": [
                                                [
                                                "tam19",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Berharap menggunakan",
                                            "value": [
                                                [
                                                "tam20",
                                                2
                                                ]
                                            ]
                                            }
                                        ]
                                    },
                                    {
                                    "name": "ASU",
                                    "responses": [
                                        {
                                        "name": "Frekuensi penggunaan",
                                        "value": [
                                            [
                                            "tam21",
                                            5
                                            ],
                                            [
                                            "tam22",
                                            3
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Durasi penggunaan",
                                        "value": [
                                            [
                                            "tam23",
                                            5
                                            ]
                                        ]
                                        }
                                    ]
                                    }
                                ]
                            }',
            'survey_id' => 1
        ]);

        SurveyResponses::create([
            'user_id' => 6,
            'first_name' => 'Amanda',
            'surname' => 'Clark',
            'email' => 'amanda.clark@example.com',
            'birth_date' => '1992-09-18',
            'gender' => 'Female',
            'profession' => 'Police',
            'educational_background' => "High School",
            'response_data' => '{
                                  "sus": {
                                    "sus1": 3,
                                    "sus2": 3,
                                    "sus3": 3,
                                    "sus4": 3,
                                    "sus5": 3,
                                    "sus6": 3,
                                    "sus7": 3,
                                    "sus8": 3,
                                    "sus9": 3,
                                    "sus10": 3
                                  },
                                  "tam": [
                                    {
                                      "name": "PEU",
                                      "responses": [
                                        {
                                          "name": "Kemudahan dipelajari",
                                          "value": [
                                            [
                                              "tam1",
                                              3
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Mudah dipahami/dimengerti",
                                          "value": [
                                            [
                                              "tam2",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Mudah sehingga mahir",
                                          "value": [
                                            [
                                              "tam3",
                                              3
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Mudah digunakan",
                                          "value": [
                                            [
                                              "tam4",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Mudah dikendalikan",
                                          "value": [
                                            [
                                              "tam5",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Mudah diingat",
                                          "value": [
                                            [
                                              "tam6",
                                              4
                                            ]
                                          ]
                                        }
                                      ]
                                    },
                                    {
                                      "name": "PU",
                                      "responses": [
                                        {
                                          "name": "Lebih cepat",
                                          "value": [
                                            [
                                              "tam7",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Meningkatkan kinerja",
                                          "value": [
                                            [
                                              "tam8",
                                              3
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Meningkatkan produktivitas",
                                          "value": [
                                            [
                                              "tam9",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Meningkatkan efektivitas",
                                          "value": [
                                            [
                                              "tam10",
                                              4
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Lebih mudah",
                                          "value": [
                                            [
                                              "tam11",
                                              4
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Bermanfaat",
                                          "value": [
                                            [
                                              "tam12",
                                              5
                                            ]
                                          ]
                                        }
                                      ]
                                    },
                                    {
                                      "name": "ATU",
                                      "responses": [
                                        {
                                          "name": "Rasa senang",
                                          "value": [
                                            [
                                              "tam13",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Menikmati",
                                          "value": [
                                            [
                                              "tam14",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Rasa bosan",
                                          "value": [
                                            [
                                              "tam15",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Tidak suka",
                                          "value": [
                                            [
                                              "tam16",
                                              3
                                            ]
                                          ]
                                        }
                                      ]
                                    },
                                    {
                                      "name": "BI",
                                        "responses": [
                                            {
                                            "name": "Menggunakan kapan saja",
                                            "value": [
                                                [
                                                "tam17",
                                                2
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menggunakan kondisi apapun",
                                            "value": [
                                                [
                                                "tam18",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Niat menggunakan terus",
                                            "value": [
                                                [
                                                "tam19",
                                                2
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Berharap menggunakan",
                                            "value": [
                                                [
                                                "tam20",
                                                3
                                                ]
                                            ]
                                            }
                                        ]
                                    },
                                    {
                                      "name": "ASU",
                                      "responses": [
                                        {
                                          "name": "Frekuensi penggunaan",
                                          "value": [
                                            [
                                              "tam21",
                                              4
                                            ],
                                            [
                                              "tam22",
                                              3
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Durasi penggunaan",
                                          "value": [
                                            [
                                              "tam23",
                                              2
                                            ]
                                          ]
                                        }
                                      ]
                                    }
                                  ]
                                }',
            'survey_id' => 1
        ]);

        SurveyResponses::create([
            'user_id' => 6,
            'first_name' => 'Amanda',
            'surname' => 'Clark',
            'email' => 'amanda.clark@example.com',
            'birth_date' => '1992-09-18',
            'gender' => 'Female',
            'profession' => 'Police',
            'educational_background' => "High School",
            'response_data' => '{
                                  "tam": [
                                    {
                                      "name": "PEU",
                                      "responses": [
                                        {
                                          "name": "Kemudahan dipelajari",
                                          "value": [
                                            [
                                              "tam1",
                                              3
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Mudah dipahami/dimengerti",
                                          "value": [
                                            [
                                              "tam2",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Mudah sehingga mahir",
                                          "value": [
                                            [
                                              "tam3",
                                              3
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Mudah digunakan",
                                          "value": [
                                            [
                                              "tam4",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Mudah dikendalikan",
                                          "value": [
                                            [
                                              "tam5",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Mudah diingat",
                                          "value": [
                                            [
                                              "tam6",
                                              4
                                            ]
                                          ]
                                        }
                                      ]
                                    },
                                    {
                                      "name": "PU",
                                      "responses": [
                                        {
                                          "name": "Lebih cepat",
                                          "value": [
                                            [
                                              "tam7",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Meningkatkan kinerja",
                                          "value": [
                                            [
                                              "tam8",
                                              3
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Meningkatkan produktivitas",
                                          "value": [
                                            [
                                              "tam9",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Meningkatkan efektivitas",
                                          "value": [
                                            [
                                              "tam10",
                                              4
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Lebih mudah",
                                          "value": [
                                            [
                                              "tam11",
                                              4
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Bermanfaat",
                                          "value": [
                                            [
                                              "tam12",
                                              5
                                            ]
                                          ]
                                        }
                                      ]
                                    },
                                    {
                                      "name": "ATU",
                                      "responses": [
                                        {
                                          "name": "Rasa senang",
                                          "value": [
                                            [
                                              "tam13",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Menikmati",
                                          "value": [
                                            [
                                              "tam14",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Rasa bosan",
                                          "value": [
                                            [
                                              "tam15",
                                              5
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Tidak suka",
                                          "value": [
                                            [
                                              "tam16",
                                              3
                                            ]
                                          ]
                                        }
                                      ]
                                    },
                                    {
                                      "name": "BI",
                                        "responses": [
                                            {
                                            "name": "Menggunakan kapan saja",
                                            "value": [
                                                [
                                                "tam17",
                                                2
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menggunakan kondisi apapun",
                                            "value": [
                                                [
                                                "tam18",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Niat menggunakan terus",
                                            "value": [
                                                [
                                                "tam19",
                                                2
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Berharap menggunakan",
                                            "value": [
                                                [
                                                "tam20",
                                                3
                                                ]
                                            ]
                                            }
                                        ]
                                    },
                                    {
                                      "name": "ASU",
                                      "responses": [
                                        {
                                          "name": "Frekuensi penggunaan",
                                          "value": [
                                            [
                                              "tam21",
                                              4
                                            ],
                                            [
                                              "tam22",
                                              3
                                            ]
                                          ]
                                        },
                                        {
                                          "name": "Durasi penggunaan",
                                          "value": [
                                            [
                                              "tam23",
                                              2
                                            ]
                                          ]
                                        }
                                      ]
                                    }
                                  ]
                                }',
            'survey_id' => 2
        ]);

        SurveyResponses::create([
            'user_id' => 5,
            'first_name' => 'David',
            'surname' => 'Lee',
            'email' => 'david.lee@example.com',
            'birth_date' => '1988-04-02',
            'gender' => 'Male',
            'profession' => 'Armed Forces',
            'educational_background' => "Associate's Degree",
            'response_data' => '{
                                "tam": [
                                    {
                                    "name": "PEU",
                                    "responses": [
                                        {
                                        "name": "Kemudahan dipelajari",
                                        "value": [
                                            [
                                            "tam1",
                                            4
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Mudah dipahami/dimengerti",
                                        "value": [
                                            [
                                            "tam2",
                                            3
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Mudah sehingga mahir",
                                        "value": [
                                            [
                                            "tam3",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Mudah digunakan",
                                        "value": [
                                            [
                                            "tam4",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Mudah dikendalikan",
                                        "value": [
                                            [
                                            "tam5",
                                            2
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Mudah diingat",
                                        "value": [
                                            [
                                            "tam6",
                                            5
                                            ]
                                        ]
                                        }
                                    ]
                                    },
                                    {
                                    "name": "PU",
                                    "responses": [
                                        {
                                        "name": "Lebih cepat",
                                        "value": [
                                            [
                                            "tam7",
                                            4
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Meningkatkan kinerja",
                                        "value": [
                                            [
                                            "tam8",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Meningkatkan produktivitas",
                                        "value": [
                                            [
                                            "tam9",
                                            3
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Meningkatkan efektivitas",
                                        "value": [
                                            [
                                            "tam10",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Lebih mudah",
                                        "value": [
                                            [
                                            "tam11",
                                            4
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Bermanfaat",
                                        "value": [
                                            [
                                            "tam12",
                                            4
                                            ]
                                        ]
                                        }
                                    ]
                                    },
                                    {
                                    "name": "ATU",
                                    "responses": [
                                        {
                                        "name": "Rasa senang",
                                        "value": [
                                            [
                                            "tam13",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Menikmati",
                                        "value": [
                                            [
                                            "tam14",
                                            5
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Rasa bosan",
                                        "value": [
                                            [
                                            "tam15",
                                            4
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Tidak suka",
                                        "value": [
                                            [
                                            "tam16",
                                            1
                                            ]
                                        ]
                                        }
                                    ]
                                    },
                                    {
                                    "name": "BI",
                                        "responses": [
                                            {
                                            "name": "Menggunakan kapan saja",
                                            "value": [
                                                [
                                                "tam17",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Menggunakan kondisi apapun",
                                            "value": [
                                                [
                                                "tam18",
                                                3
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Niat menggunakan terus",
                                            "value": [
                                                [
                                                "tam19",
                                                4
                                                ]
                                            ]
                                            },
                                            {
                                            "name": "Berharap menggunakan",
                                            "value": [
                                                [
                                                "tam20",
                                                2
                                                ]
                                            ]
                                            }
                                        ]
                                    },
                                    {
                                    "name": "ASU",
                                    "responses": [
                                        {
                                        "name": "Frekuensi penggunaan",
                                        "value": [
                                            [
                                            "tam21",
                                            5
                                            ],
                                            [
                                            "tam22",
                                            3
                                            ]
                                        ]
                                        },
                                        {
                                        "name": "Durasi penggunaan",
                                        "value": [
                                            [
                                            "tam23",
                                            5
                                            ]
                                        ]
                                        }
                                    ]
                                    }
                                ]
                            }',
            'survey_id' => 2
        ]);
    }
}
