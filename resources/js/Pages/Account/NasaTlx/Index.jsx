import React, { useState } from "react";
import { Head, usePage, Link } from "@inertiajs/inertia-react";
import { Inertia } from "@inertiajs/inertia";
import ReactMarkdown from "react-markdown";
import hasAnyPermission from "../../../Utils/Permissions";
import LayoutAccount from "../../../Layouts/Account";
import CardContent from "../../../Layouts/CardContent";
import AccordionLayout from "../../../Layouts/Accordion";
import PieChart from "../../../Components/PieChart";
import InfoCard from "../../../Components/CardInfo";

export default function NasaTlxIndex() {
    const {
        auth,
        survey,
        surveyTitles,
        respondentCount,
        averageNasaTlx,
        currentSurveyTitle,
        demographicRespondents,
        nasaTlxChartData,
        nasaTlxSurveyResults,
        averageDimension,
        resumeDescription,
        aiRecommendation,
    } = usePage().props;

    const [aiResult, setAiResult] = useState(
        aiRecommendation?.ai_recommendation || ""
    );
    const [aiLoading, setAiLoading] = useState(false);
    const [aiError, setAiError] = useState("");
    const [lastGenerated, setLastGenerated] = useState(
        aiRecommendation?.generated_at || null
    );

    // Export modal states
    const [showExportModal, setShowExportModal] = useState(false);
    const [selectedSurveys, setSelectedSurveys] = useState([]);
    const [exportLoading, setExportLoading] = useState(false);

    const name = `${auth.user.first_name} ${auth.user.surname}`;

    const getDemographicData = (data, category) => {
        const labels = Object.keys(data);
        const counts = Object.values(data);

        return {
            labels,
            datasets: [
                {
                    label: category,
                    data: counts,
                },
            ],
        };
    };

    const dimensionColors = {
        mental_demand: "#FF6B6B",
        physical_demand: "#4ECDC4",
        temporal_demand: "#45B7D1",
        performance: "#FFA07A",
        effort: "#98D8C8",
        frustration: "#F7DC6F"
    };

    const getDimensionChartData = () => {
        if (!averageDimension) return null;

        return {
            labels: [
                "Mental Demand",
                "Physical Demand",
                "Temporal Demand",
                "Performance",
                "Effort",
                "Frustration"
            ],
            datasets: [
                {
                    label: "Average Score",
                    data: [
                        averageDimension.mental_demand,
                        averageDimension.physical_demand,
                        averageDimension.temporal_demand,
                        averageDimension.performance,
                        averageDimension.effort,
                        averageDimension.frustration
                    ],
                    backgroundColor: Object.values(dimensionColors),
                    borderColor: Object.values(dimensionColors),
                    borderWidth: 1,
                },
            ],
        };
    };

    const demographicsData = demographicRespondents
        ? Object.keys(demographicRespondents).map((category) => ({
              category,
              data: getDemographicData(
                  demographicRespondents[category],
                  category
              ),
          }))
        : [];

    const handleExport = () => {
        setShowExportModal(true);
    };

    const handleSurveySelection = (surveyId) => {
        setSelectedSurveys((prev) => {
            if (prev.includes(surveyId)) {
                return prev.filter((id) => id !== surveyId);
            } else {
                return [...prev, surveyId];
            }
        });
    };

    const handleSelectAll = () => {
        if (selectedSurveys.length === surveyTitles.length) {
            setSelectedSurveys([]);
        } else {
            setSelectedSurveys(surveyTitles.map((survey) => survey.id));
        }
    };

    const handleConfirmExport = async () => {
        if (selectedSurveys.length === 0) {
            alert("Pilih minimal satu survei untuk diekspor");
            return;
        }

        setExportLoading(true);
        try {
            const surveyIds = selectedSurveys.join(",");
            window.location.href = `/account/responses/nasa-tlx/export?surveys=${surveyIds}`;
            setShowExportModal(false);
            setSelectedSurveys([]);
        } catch (error) {
            console.error("Export error:", error);
        } finally {
            setExportLoading(false);
        }
    };

    const generateAiRecommendation = async () => {
        setAiLoading(true);
        setAiError("");
        try {
            const response = await fetch(`/account/nasa-tlx/${survey.id}/ai-recommendation`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                        ?.content,
                },
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || "Gagal membuat rekomendasi");
            }

            setAiResult(data.recommendation);
            setLastGenerated(data.generated_at);
        } catch (error) {
            setAiError(error.message);
        } finally {
            setAiLoading(false);
        }
    };

    return (
        <LayoutAccount>
            <Head title="NASA-TLX Results" />
            <CardContent
                title={currentSurveyTitle}
                description="Hasil Penilaian NASA-TLX"
            >
                <div className="flex gap-4 mb-4">
                    <select
                        value={
                            surveyTitles.find((s) => s.id === survey.id)?.id || ""
                        }
                        onChange={(e) =>
                            Inertia.get(`/account/nasa-tlx/${e.target.value}`)
                        }
                        className="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        {surveyTitles.map((surveyTitle) => (
                            <option key={surveyTitle.id} value={surveyTitle.id}>
                                {surveyTitle.title}
                            </option>
                        ))}
                    </select>

                    {hasAnyPermission(auth, [
                        "nasa_tlx.export",
                    ]) && (
                        <button
                            onClick={handleExport}
                            className="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600"
                        >
                            Export to Excel
                        </button>
                    )}
                </div>

                {/* Summary Statistics */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div className="bg-blue-50 p-6 rounded-lg">
                        <h3 className="text-lg font-semibold text-gray-700 mb-2">
                            Total Responden
                        </h3>
                        <p className="text-3xl font-bold text-blue-600">
                            {respondentCount}
                        </p>
                    </div>

                    <div className="bg-green-50 p-6 rounded-lg">
                        <h3 className="text-lg font-semibold text-gray-700 mb-2">
                            Skor Rata-rata NASA-TLX
                        </h3>
                        <p className="text-3xl font-bold text-green-600">
                            {averageNasaTlx}
                        </p>
                    </div>
                </div>

                {/* Dimension Chart */}
                {getDimensionChartData() && (
                    <div className="bg-white p-6 rounded-lg shadow mb-6">
                        <h3 className="text-lg font-semibold text-gray-700 mb-4">
                            Rata-rata per Dimensi
                        </h3>
                        <PieChart data={getDimensionChartData()} />
                    </div>
                )}

                {/* Resume Description */}
                {resumeDescription && (
                    <div className="bg-yellow-50 p-6 rounded-lg shadow mb-6 border-l-4 border-yellow-400">
                        <h3 className="text-lg font-semibold text-gray-700 mb-2">
                            Ringkasan Hasil
                        </h3>
                        <p className="text-gray-700">{resumeDescription}</p>
                    </div>
                )}

                {/* AI Recommendation */}
                {hasAnyPermission(auth, ["dashboard.index.full"]) && (
                    <AccordionLayout title="AI-Powered Recommendation">
                        <div className="space-y-4">
                            <button
                                onClick={generateAiRecommendation}
                                disabled={aiLoading}
                                className="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 disabled:opacity-50"
                            >
                                {aiLoading
                                    ? "Generating..."
                                    : "Generate Recommendation"}
                            </button>

                            {lastGenerated && (
                                <p className="text-sm text-gray-500">
                                    Last generated: {lastGenerated}
                                </p>
                            )}

                            {aiError && (
                                <div className="bg-red-50 p-4 rounded text-red-700">
                                    {aiError}
                                </div>
                            )}

                            {aiResult && (
                                <div className="bg-blue-50 p-4 rounded">
                                    <ReactMarkdown>{aiResult}</ReactMarkdown>
                                </div>
                            )}
                        </div>
                    </AccordionLayout>
                )}

                {/* Respondent Results Table */}
                <AccordionLayout title="Detail Responden">
                    <div className="overflow-x-auto">
                        <table className="w-full border-collapse border border-gray-300">
                            <thead className="bg-gray-100">
                                <tr>
                                    <th className="border border-gray-300 p-3 text-left">Nama</th>
                                    <th className="border border-gray-300 p-3 text-left">Mental</th>
                                    <th className="border border-gray-300 p-3 text-left">Physical</th>
                                    <th className="border border-gray-300 p-3 text-left">Temporal</th>
                                    <th className="border border-gray-300 p-3 text-left">Performance</th>
                                    <th className="border border-gray-300 p-3 text-left">Effort</th>
                                    <th className="border border-gray-300 p-3 text-left">Frustration</th>
                                    <th className="border border-gray-300 p-3 text-left">Skor Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                {nasaTlxSurveyResults.map((result) => (
                                    <tr key={result.id}>
                                        <td className="border border-gray-300 p-3">{result.respondentName}</td>
                                        <td className="border border-gray-300 p-3">{result.dimensions.mental_demand}</td>
                                        <td className="border border-gray-300 p-3">{result.dimensions.physical_demand}</td>
                                        <td className="border border-gray-300 p-3">{result.dimensions.temporal_demand}</td>
                                        <td className="border border-gray-300 p-3">{result.dimensions.performance}</td>
                                        <td className="border border-gray-300 p-3">{result.dimensions.effort}</td>
                                        <td className="border border-gray-300 p-3">{result.dimensions.frustration}</td>
                                        <td className="border border-gray-300 p-3 font-bold">{result.finalScore}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </AccordionLayout>

                {/* Demographics */}
                {demographicsData.length > 0 && (
                    <AccordionLayout title="Data Demografis Responden">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {demographicsData.map((demographic) => (
                                <div key={demographic.category} className="bg-white p-4 rounded">
                                    <h4 className="font-semibold text-gray-700 mb-2 capitalize">
                                        {demographic.category}
                                    </h4>
                                    <PieChart data={demographic.data} />
                                </div>
                            ))}
                        </div>
                    </AccordionLayout>
                )}
            </CardContent>

            {/* Export Modal */}
            {showExportModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white p-6 rounded-lg max-w-md w-full">
                        <h2 className="text-xl font-bold mb-4">Pilih Survei untuk Diekspor</h2>

                        <div className="space-y-2 max-h-64 overflow-y-auto mb-4">
                            <label className="flex items-center p-2 hover:bg-gray-100 rounded">
                                <input
                                    type="checkbox"
                                    checked={
                                        selectedSurveys.length ===
                                        surveyTitles.length
                                    }
                                    onChange={handleSelectAll}
                                    className="mr-2"
                                />
                                <span className="font-semibold">Pilih Semua</span>
                            </label>

                            {surveyTitles.map((surveyTitle) => (
                                <label
                                    key={surveyTitle.id}
                                    className="flex items-center p-2 hover:bg-gray-100 rounded"
                                >
                                    <input
                                        type="checkbox"
                                        checked={selectedSurveys.includes(
                                            surveyTitle.id
                                        )}
                                        onChange={() =>
                                            handleSurveySelection(surveyTitle.id)
                                        }
                                        className="mr-2"
                                    />
                                    <span>{surveyTitle.title}</span>
                                </label>
                            ))}
                        </div>

                        <div className="flex gap-2">
                            <button
                                onClick={() => setShowExportModal(false)}
                                className="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
                            >
                                Batal
                            </button>
                            <button
                                onClick={handleConfirmExport}
                                disabled={exportLoading}
                                className="flex-1 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 disabled:opacity-50"
                            >
                                {exportLoading ? "Exporting..." : "Export"}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </LayoutAccount>
    );
}
