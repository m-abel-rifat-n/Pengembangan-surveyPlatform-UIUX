import React from "react";
import { Link } from "@inertiajs/inertia-react";

const TableDashboardSurvey = ({ surveyData, surveys }) => {
    if (!surveyData || surveyData.length === 0) {
        return <div>Tidak Ada Data Survei</div>;
    }

    return (
        <div className="table-responsive">
            <table className="table table-striped table-bordered">
                <thead className="thead">
                    <tr>
                        <th>No</th>
                        <th>Judul Survei</th>
                        <th>Status</th>
                        <th>Jumlah Responden</th>
                        <th>Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    {surveyData.map((survey, index) => (
                        <tr key={index}>
                            <td>
                                {++index +
                                    (surveys.current_page - 1) *
                                        surveys.per_page}
                            </td>
                            <td>{survey.title}</td>
                            <td>{survey.status}</td>
                            <td>{survey.response_count}</td>
                            <td>
                                <div className="d-flex gap-2">
                                    {survey.method_ids.map(
                                        (methodId, index) => {
                                            if (methodId == 1) {
                                                return (
                                                    <Link
                                                        href={`sus/${survey.survey_id}`}
                                                        className="btn btn-sm btn-style border-0 shadow"
                                                        type="button"
                                                    >
                                                        SUS
                                                    </Link>
                                                );
                                            } else if (methodId == 2) {
                                                return (
                                                    <Link
                                                        href={`tam/${survey.survey_id}`}
                                                        className="btn btn-sm btn-style border-0 shadow"
                                                        type="button"
                                                    >
                                                        TAM
                                                    </Link>
                                                );
                                            } else if (methodId == 3) {
                                                return (
                                                    <Link
                                                        href={`ab_test/${survey.survey_id}`}
                                                        className="btn btn-sm btn-style border-0 shadow"
                                                        type="button"
                                                    >
                                                        A/B
                                                    </Link>
                                                );
                                            } else if (methodId == 4) {
                                                return (
                                                    <Link
                                                        href={`wcag_test/${survey.survey_id}`}
                                                        className="btn btn-sm btn-style border-0 shadow"
                                                        type="button"
                                                    >
                                                        WCAG
                                                    </Link>
                                                );
                                            } else if (methodId == 5) {
                                                return (
                                                    <Link
                                                        href={`nasa-tlx/${survey.survey_id}`}
                                                        className="btn btn-sm btn-style border-0 shadow"
                                                        type="button"
                                                    >
                                                        NASA-TLX
                                                    </Link>
                                                );
                                            } else if (methodId == 6) {
                                                return (
                                                    <Link
                                                        href={`visawi-s/${survey.survey_id}`}
                                                        className="btn btn-sm btn-style border-0 shadow"
                                                        type="button"
                                                    >
                                                        VisAWI-S
                                                    </Link>
                                                );
                                            }
                                        }
                                    )}
                                </div>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default TableDashboardSurvey;
