import React from "react";
import { Link } from "@inertiajs/inertia-react";
import Pagination from "./Pagination";

const TableCertificates = ({ certificates }) => {
    if (!certificates || certificates.length === 0) {
        return <div>Tidak Ada Data Survei</div>;
    }

    return (
        <div className="table-responsive">
            <table className="table table-striped table-bordered">
                <thead className="thead">
                    <tr>
                        <th>No</th>
                        <th>File Name</th>
                        {certificates.data[0].user && <th>User Name</th>}
                        <th>Status</th>
                        <th>Description</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    {certificates.data.map((certificate, index) => (
                        <tr key={index}>
                            <td>{index + 1}</td>
                            <td>{certificate.original_certificate}</td>
                            {certificates.data[0].user && (
                                <td>
                                    {certificate.user.first_name +
                                        " " +
                                        certificate.user.surname}
                                </td>
                            )}
                            <td>{certificate.status}</td>
                            <td>{certificate.description}</td>
                            <td>{certificate.updated_at}</td>
                        </tr>
                    ))}
                </tbody>
            </table>

            <Pagination links={certificates.links} align={"end"} />
        </div>
    );
};

export default TableCertificates;
