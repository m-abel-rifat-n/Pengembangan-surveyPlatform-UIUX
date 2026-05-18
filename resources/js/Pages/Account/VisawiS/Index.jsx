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

export default function VisawiSIndex() {
    const {
        auth,
        survey,
        surveyTitles,
        respondentCount,
        averageVisawiS,
        currentSurveyTitle,
        demographicRespondents,
        visawiChartData,
        visawiSurveyResults,
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

    const dimensionLabels = {
        simplicity: "Simplicity",
        diversity: "Diversity",
        colorfulness: "Colorfulness",
        craftsmanship: "Craftsmanship",
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
            window.location.href = `/account/responses/visawi-s/export?surveys=${surveyIds}`;
            setShowExportModal(false);
            setSelectedSurveys([]);
        } catch (error) {
            alert("Terjadi kesalahan saat mengekspor data");
        } finally {
            setExportLoading(false);
        }
    };

    const handleGenerateAI = async () => {
        if (!resumeDescription) {
            setAiError("Tidak ada data untuk dianalisis");
            return;
        }

        setAiLoading(true);
        setAiError("");

        try {
            const response = await fetch(
                `/account/visawi-s/${survey.id}/ai-recommendation`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                }
            );

            const data = await response.json();

            if (response.ok && data.success) {
                setAiResult(data.recommendation);
                setLastGenerated(data.generated_at);
                setAiError("");
            } else {
                setAiError(data.error || "Gagal menghasilkan rekomendasi AI");
            }
        } catch (error) {
            setAiError("Terjadi kesalahan saat menghubungi server");
        } finally {
            setAiLoading(false);
        }
    };

    const getAverageDimensionData = () => {
        if (!averageDimension) return null;

        return {
            labels: [
                "Simplicity",
                "Diversity",
                "Colorfulness",
                "Craftsmanship"
            ],
            datasets: [
                {
                    label: "Rata-rata (1-7)",
                    data: [
                        averageDimension.simplicity,
                        averageDimension.diversity,
                        averageDimension.colorfulness,
                        averageDimension.craftsmanship,
                    ],
                    backgroundColor: [
                        "#FF6B6B",
                        "#4ECDC4",
                        "#45B7D1",
                        "#FFA07A"
                    ],
                },
            ],
        };
    };

    return (
        <>
            <Head>
                <title>VisAWI-S Result - UIX-Probe</title>
            </Head>
            <LayoutAccount>
                <div className="m-3">
                    <div className="row card-body border-0 shadow-sm mb-2">
                        <div className="col-md-4">
                            Selamat Datang, <strong>{name}</strong> <br />
                            {currentSurveyTitle ? (
                                <span>
                                    Hasil :{" "}
                                    <strong>{currentSurveyTitle}</strong>
                                </span>
                            ) : (
                                <strong>Pilih survei terlebih dahulu.</strong>
                            )}
                        </div>
                        <div className="col-md-4 text-center">
                            {(hasAnyPermission(["visawi_s.export"]) &&
                                hasAnyPermission(["visawi_s.responses"])) && (
                                    <button
                                        className="btn btn-style"
                                        onClick={handleExport}
                                        style={{
                                            minWidth: "180px",
                                            boxShadow:
                                                "0 4px 8px rgba(0,0,0,0.1)",
                                        }}
                                    >
                                        <i className="fas fa-download me-2"></i>
                                        Export to Excel
                                    </button>
                                )}
                        </div>
                        <div className="col-md-4 text-end">
                            <div className="mb-2">
                                <div className="dropdown">
                                    <button
                                        className="btn select-btn dropdown-toggle"
                                        type="button"
                                        id="dropdownMenuButton"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        style={{ width: "100%" }}
                                    >
                                        Pilih Survey
                                    </button>
                                    <ul
                                        className="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="dropdownMenuButton"
                                        style={{
                                            maxHeight: "200px",
                                            width: "100%",
                                            overflowY: "scroll",
                                        }}
                                    >
                                        {surveyTitles.map((survey) => (
                                            <li key={survey.id}>
                                                <a
                                                    className="dropdown-item"
                                                    href="#"
                                                    onClick={(event) => {
                                                        event.preventDefault();
                                                        Inertia.get(
                                                            `/account/visawi-s/${survey.id}`
                                                        );
                                                    }}
                                                >
                                                    {survey.title}
                                                </a>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {hasAnyPermission(["visawi_s.statistics"]) && (
                        <>
                            <div className="row mt-2">
                                <InfoCard
                                    icon="fa-users"
                                    background="success"
                                    value={respondentCount}
                                    title="Jumlah Responden"
                                />
                                <InfoCard
                                    icon="fa-star"
                                    background="primary"
                                    value={`${averageVisawiS} dari 7`}
                                    title="Skor VisAWI-S Total"
                                />
                                <InfoCard
                                    icon="fa-palette"
                                    background="info"
                                    value={averageDimension ? (averageDimension.simplicity > 5 ? "Baik" : averageDimension.simplicity > 3 ? "Cukup" : "Perlu Perbaikan") : "N/A"}
                                    title="Estetika Visual"
                                />
                            </div>

                            {resumeDescription !== null ? (
                                <CardContent title="Kesimpulan">
                                    <div className="text-center">
                                        {resumeDescription}
                                    </div>
                                    <hr />
                                    {averageDimension && (
                                        <div className="row justify-content-center">
                                            {Object.entries(averageDimension).map(
                                                ([key, value]) => (
                                                    <div
                                                        className="text-center col-lg-4 col-md-6 mb-4 mx-auto"
                                                        key={key}
                                                    >
                                                        <strong>{dimensionLabels[key]}:</strong> {value}
                                                    </div>
                                                )
                                            )}
                                        </div>
                                    )}
                                    <hr />
                                    <div className="row">
                                        <div className="col-lg-4 col-md-12 mb-4">
                                            <div className="d-flex align-items-center justify-content-center">
                                                <p>
                                                    Positif jika rata-rata{" "}
                                                    {">= 5.5"}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="col-lg-4 col-md-12 mb-4">
                                            <div className="d-flex align-items-center justify-content-center">
                                                <p>
                                                    Cukup jika rata-rata{" "}
                                                    {"> 3.5 & < 5.5"}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="col-lg-4 col-md-12 mb-4">
                                            <div className="d-flex align-items-center justify-content-center">
                                                <p>
                                                    Negatif jika rata-rata{" "}
                                                    {"<= 3.5"}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </CardContent>
                            ) : null}

                            {/* AI Recommendation Section */}
                            <CardContent title="Rekomendasi AI">
                                <div className="d-flex justify-content-between align-items-center mb-3">
                                    <h6 className="mb-0">
                                        Solusi dan Saran Berbasis AI
                                    </h6>
                                    <button
                                        className="btn btn-primary"
                                        onClick={handleGenerateAI}
                                        disabled={
                                            aiLoading || !resumeDescription
                                        }
                                    >
                                        {aiLoading ? (
                                            <>
                                                <span
                                                    className="spinner-border spinner-border-sm me-2"
                                                    role="status"
                                                    aria-hidden="true"
                                                ></span>
                                                Menghasilkan...
                                            </>
                                        ) : (
                                            <>
                                                <i className="fas fa-robot me-2"></i>
                                                {aiResult
                                                    ? "Regenerate AI"
                                                    : "Generate AI"}
                                            </>
                                        )}
                                    </button>
                                </div>

                                {aiError && (
                                    <div
                                        className="alert alert-danger"
                                        role="alert"
                                    >
                                        <i className="fas fa-exclamation-triangle me-2"></i>
                                        {aiError}
                                    </div>
                                )}

                                {aiResult ? (
                                    <div>
                                        {lastGenerated && (
                                            <small className="text-muted mb-3 d-block">
                                                <i className="fas fa-clock me-1"></i>
                                                Terakhir dihasilkan:{" "}
                                                {new Date(
                                                    lastGenerated
                                                ).toLocaleString("id-ID")}
                                            </small>
                                        )}
                                        <div
                                            className="ai-recommendation-content"
                                            style={{
                                                backgroundColor: "#f8f9fa",
                                                padding: "20px",
                                                borderRadius: "8px",
                                                border: "1px solid #dee2e6",
                                                lineHeight: "1.6",
                                            }}
                                        >
                                            <ReactMarkdown
                                                components={{
                                                    h1: ({node, ...props}) => <h5 className="fw-bold mt-3 mb-2" {...props} />,
                                                    h2: ({node, ...props}) => <h6 className="fw-bold mt-3 mb-2" {...props} />,
                                                    h3: ({node, ...props}) => <h6 className="fw-semibold mt-2 mb-1" {...props} />,
                                                    ul: ({node, ...props}) => <ul style={{paddingLeft: "1.5rem", marginBottom: "0.75rem"}} {...props} />,
                                                    ol: ({node, ...props}) => <ol style={{paddingLeft: "1.5rem", marginBottom: "0.75rem"}} {...props} />,
                                                    li: ({node, ...props}) => <li style={{marginBottom: "0.25rem"}} {...props} />,
                                                    p:  ({node, ...props}) => <p style={{marginBottom: "0.5rem"}} {...props} />,
                                                    strong: ({node, ...props}) => <strong className="fw-semibold" {...props} />,
                                                }}
                                            >
                                                {aiResult}
                                            </ReactMarkdown>
                                        </div>
                                    </div>
                                ) : (
                                    <div className="text-center py-4">
                                        <i className="fas fa-robot fa-3x text-muted mb-3"></i>
                                        <p className="text-muted">
                                            Klik tombol "Generate AI" untuk
                                            mendapatkan rekomendasi dan solusi
                                            berbasis AI berdasarkan hasil
                                            analisis VisAWI-S.
                                        </p>
                                    </div>
                                )}
                            </CardContent>
                        </>
                    )}

                    <AccordionLayout
                        title="Demografi Responden"
                        defaultOpen={true}
                    >
                        {demographicsData.length > 0 ? (
                            <div className="row justify-content-center">
                                {demographicsData.map((item, index) => (
                                    <div
                                        className="col-lg-4 col-md-6 mb-4 mx-auto"
                                        key={index}
                                    >
                                        <div className="card">
                                            <div className="card-body">
                                                <h6 className="card-title text-center">
                                                    {item.category
                                                        .charAt(0)
                                                        .toUpperCase() +
                                                        item.category
                                                            .slice(1)
                                                            .replace(
                                                                /_/g,
                                                                " "
                                                            )}{" "}
                                                </h6>
                                                <PieChart data={item.data} />
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="text-center">Tidak ada data</div>
                        )}
                    </AccordionLayout>

                    {hasAnyPermission(["visawi_s.charts"]) && (
                        <AccordionLayout
                            title="Grafik Dimensi VisAWI-S"
                            defaultOpen={true}
                        >
                            {getAverageDimensionData() ? (
                                <div className="row justify-content-center">
                                    <div className="col-lg-8 col-md-10 mb-4 mx-auto">
                                        <div className="card">
                                            <div className="card-body">
                                                <h6 className="card-title text-center">
                                                    Rata-rata per Dimensi
                                                </h6>
                                                <PieChart
                                                    data={getAverageDimensionData()}
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ) : (
                                <div className="text-center">
                                    Tidak ada data
                                </div>
                            )}
                        </AccordionLayout>
                    )}

                    {hasAnyPermission(["visawi_s.responses"]) && (
                        <AccordionLayout
                            title="Tabel Hasil"
                            defaultOpen={false}
                        >
                            {visawiSurveyResults.length > 0 ? (
                                <div>
                                    <div className="d-flex justify-content-between align-items-center mb-4">
                                        <h4>Hasil VisAWI-S</h4>
                                    </div>
                                    <div className="table-responsive">
                                        <table className="table table-striped">
                                            <thead className="table-light">
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Simplicity</th>
                                                    <th>Diversity</th>
                                                    <th>Colorfulness</th>
                                                    <th>Craftsmanship</th>
                                                    <th>Skor Akhir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {visawiSurveyResults.map((result) => (
                                                    <tr key={result.id}>
                                                        <td>{result.respondentName}</td>
                                                        <td>{result.dimensions.simplicity}</td>
                                                        <td>{result.dimensions.diversity}</td>
                                                        <td>{result.dimensions.colorfulness}</td>
                                                        <td>{result.dimensions.craftsmanship}</td>
                                                        <td className="fw-bold">{result.finalScore}</td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            ) : (
                                <div className="text-center">
                                    Tidak ada data
                                </div>
                            )}
                        </AccordionLayout>
                    )}
                </div>

                {/* Export Modal */}
                {showExportModal && (
                    <div
                        className="modal fade show"
                        style={{ display: "block" }}
                        tabIndex="-1"
                    >
                        <div className="modal-dialog modal-lg">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title">
                                        <i className="fas fa-download me-2"></i>
                                        Export Data VisAWI-S
                                    </h5>
                                    <button
                                        type="button"
                                        className="btn-close"
                                        onClick={() => {
                                            setShowExportModal(false);
                                            setSelectedSurveys([]);
                                        }}
                                    ></button>
                                </div>
                                <div className="modal-body">
                                    <div className="mb-3">
                                        <p className="text-muted">
                                            Pilih survei yang ingin diekspor ke
                                            Excel:
                                        </p>
                                    </div>

                                    <div className="mb-3">
                                        <div className="form-check">
                                            <input
                                                className="form-check-input"
                                                type="checkbox"
                                                id="selectAll"
                                                checked={
                                                    selectedSurveys.length ===
                                                    surveyTitles.length
                                                }
                                                onChange={handleSelectAll}
                                            />
                                            <label
                                                className="form-check-label fw-bold"
                                                htmlFor="selectAll"
                                            >
                                                Pilih Semua
                                            </label>
                                        </div>
                                        <hr />
                                    </div>

                                    <div
                                        className="survey-list"
                                        style={{
                                            maxHeight: "300px",
                                            overflowY: "auto",
                                        }}
                                    >
                                        {surveyTitles.map((surveyItem) => (
                                            <div
                                                key={surveyItem.id}
                                                className="form-check mb-2"
                                            >
                                                <input
                                                    className="form-check-input"
                                                    type="checkbox"
                                                    id={`survey-${surveyItem.id}`}
                                                    checked={selectedSurveys.includes(
                                                        surveyItem.id
                                                    )}
                                                    onChange={() =>
                                                        handleSurveySelection(
                                                            surveyItem.id
                                                        )
                                                    }
                                                />
                                                <label
                                                    className="form-check-label"
                                                    htmlFor={`survey-${surveyItem.id}`}
                                                >
                                                    {surveyItem.title}
                                                </label>
                                            </div>
                                        ))}
                                    </div>

                                    {selectedSurveys.length > 0 && (
                                        <div className="mt-3 p-3 bg-light rounded">
                                            <small className="text-muted">
                                                <i className="fas fa-info-circle me-1"></i>
                                                {selectedSurveys.length} survei
                                                dipilih untuk diekspor
                                            </small>
                                        </div>
                                    )}
                                </div>
                                <div className="modal-footer">
                                    <button
                                        type="button"
                                        className="btn btn-secondary"
                                        onClick={() => {
                                            setShowExportModal(false);
                                            setSelectedSurveys([]);
                                        }}
                                    >
                                        Batal
                                    </button>
                                    <button
                                        type="button"
                                        className="btn btn-primary"
                                        onClick={handleConfirmExport}
                                        disabled={
                                            selectedSurveys.length === 0 ||
                                            exportLoading
                                        }
                                    >
                                        {exportLoading ? (
                                            <>
                                                <span
                                                    className="spinner-border spinner-border-sm me-2"
                                                    role="status"
                                                    aria-hidden="true"
                                                ></span>
                                                Mengekspor...
                                            </>
                                        ) : (
                                            <>
                                                <i className="fas fa-download me-2"></i>
                                                Export ({selectedSurveys.length}
                                                )
                                            </>
                                        )}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
                {showExportModal && (
                    <div className="modal-backdrop fade show"></div>
                )}
            </LayoutAccount>
        </>
    );
}
