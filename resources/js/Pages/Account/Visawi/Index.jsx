import React from "react";
import { Head, usePage } from "@inertiajs/inertia-react";
import LayoutAccount from "../../../Layouts/Account";
import CardContent from "../../../Layouts/CardContent";
import PieChart from "../../../Components/PieChart";
import InfoCard from "../../../Components/CardInfo";

export default function VisawiIndex() {
    const {
        auth,
        survey,
        surveyTitles,
        respondentCount,
        dimensionAverages,
        finalScore,
        demographicRespondents,
        resumeDescription,
        aiRecommendation,
    } = usePage().props;

    const [aiResult, setAiResult] = React.useState(
        aiRecommendation?.ai_recommendation || ""
    );
    const [aiLoading, setAiLoading] = React.useState(false);
    const [aiError, setAiError] = React.useState("");
    const [lastGenerated, setLastGenerated] = React.useState(
        aiRecommendation?.generated_at || null
    );

    const [showExportModal, setShowExportModal] = React.useState(false);
    const [selectedSurveys, setSelectedSurveys] = React.useState([]);
    const [exportLoading, setExportLoading] = React.useState(false);

    const name = `${auth.user.first_name} ${auth.user.surname}`;

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

    const dimensionLabels = {
        simplicity: "Simplicity",
        diversity: "Diversity",
        colorfulness: "Colorfulness",
        craftsmanship: "Craftsmanship",
    };

    return (
        <>
            <Head title="VisAWI-S Results" />
            <LayoutAccount>
                <CardContent title="VisAWI-S Results">
                    <div className="row g-3 mb-4">
                        <div className="col-md-6">
                            <InfoCard
                                title="Total Respondents"
                                value={respondentCount}
                                variant="primary"
                            />
                        </div>
                        <div className="col-md-6">
                            <InfoCard
                                title="Final Score"
                                value={`${finalScore} / 7`}
                                variant="success"
                            />
                        </div>
                    </div>

                    <div className="card mb-4">
                        <div className="card-header bg-light">
                            <h5 className="mb-0">Dimension Averages</h5>
                        </div>
                        <div className="card-body">
                            <div className="row g-3">
                                {Object.entries(dimensionAverages).map(
                                    ([key, value]) => (
                                        <div key={key} className="col-md-6">
                                            <div className="d-flex justify-content-between align-items-center">
                                                <span className="fw-semibold">
                                                    {dimensionLabels[key]}:
                                                </span>
                                                <span className="badge bg-success">
                                                    {value}
                                                </span>
                                            </div>
                                        </div>
                                    )
                                )}
                            </div>
                        </div>
                    </div>

                    {resumeDescription && (
                        <div className="alert alert-info mb-4">
                            <h5 className="fw-bold">Kesimpulan</h5>
                            <p className="mb-0">{resumeDescription}</p>
                        </div>
                    )}

                    {demographicRespondents && (
                        <div className="card mb-4">
                            <div className="card-header bg-light">
                                <h5 className="mb-0">
                                    Demografi Responden
                                </h5>
                            </div>
                            <div className="card-body">
                                <div className="row g-4">
                                    {Object.entries(demographicRespondents).map(
                                        ([key, values]) => (
                                            <div
                                                key={key}
                                                className="col-md-6"
                                            >
                                                <div className="text-center">
                                                    <h6 className="fw-bold mb-3 text-capitalize">
                                                        {key.replace(
                                                            /_/g,
                                                            " "
                                                        )}
                                                    </h6>
                                                    <PieChart
                                                        data={values}
                                                        type="doughnut"
                                                        height={300}
                                                    />
                                                </div>
                                            </div>
                                        )
                                    )}
                                </div>
                            </div>
                        </div>
                    )}

                    {aiResult && (
                        <div className="alert alert-success mb-4">
                            <h5 className="fw-bold">AI Recommendation</h5>
                            <p className="mb-2">{aiResult}</p>
                            {lastGenerated && (
                                <small className="text-muted">
                                    Generated: {lastGenerated}
                                </small>
                            )}
                        </div>
                    )}

                    {!aiResult && resumeDescription && (
                        <button
                            className="btn btn-primary"
                            onClick={handleGenerateAI}
                            disabled={aiLoading}
                        >
                            {aiLoading ? (
                                <>
                                    <span
                                        className="spinner-border spinner-border-sm me-2"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>
                                    Generating...
                                </>
                            ) : (
                                <>
                                    <i className="fas fa-magic me-2"></i>
                                    Generate AI Recommendation
                                </>
                            )}
                        </button>
                    )}

                    {aiError && (
                        <div className="alert alert-danger mt-3">
                            {aiError}
                        </div>
                    )}
                </CardContent>
            </LayoutAccount>
        </>
    );
}
