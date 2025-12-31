import React from "react";
import { Link } from "@inertiajs/inertia-react";

const TableDashboardFilledSurvey = ({ surveyFilled }) => {
    if (!surveyFilled || surveyFilled.data.length === 0) {
        return <div className="text-center">Tidak Ada Data Survei</div>;
    }
    return (
        <div className="table-responsive">
            <table className="table table-striped table-bordered">
                <thead className="thead">
                    <tr>
                        <th>No</th>
                        <th>Judul Survei</th>
                        <th>Pemilik Survei</th>
                        <th>Waktu Respon Disimpan</th>
                    </tr>
                </thead>
                <tbody>
                    {surveyFilled.data.map(
                        (filled, index) => (
                            (
                                <tr key={index}>
                                    <td>
                                        {++index +
                                            (surveyFilled.current_page - 1) *
                                                surveyFilled.per_page}
                                    </td>
                                    <td>{filled.survey.title}</td>
                                    <td>{filled.user.first_name + " " + filled.user.surname}</td>
                                    <td>{filled.created_at}</td>
                                </tr>
                            )
                        )
                    )}
                </tbody>
            </table>
        </div>
    );
};

export default TableDashboardFilledSurvey;
