import React from "react";

const CertificateCard = ({
    background,
    certificate,
    selectedCertificateId,
    handleCardClick,
}) => {
    return (
        <div
            key={certificate.id}
            className={`mb-3 ${
                selectedCertificateId === certificate.id ? "selected" : ""
            }`}
            onClick={() =>
                handleCardClick(
                    certificate.id,
                    certificate.certificate,
                    certificate.original_certificate
                )
            }
        >
            <div
                className="card p-2"
                style={{
                    backgroundColor:
                        selectedCertificateId === certificate.id
                            ? "#F9DC5C"
                            : background,
                    color: "white",
                    boxShadow:
                        selectedCertificateId === certificate.id
                            ? "5px 5px 10px rgba(0, 0, 0, 0.5)"
                            : "none",
                    borderRadius: "8px",
                    transition: "all 0.3s ease-in-out",
                }}
                onMouseEnter={(e) => {
                    e.currentTarget.style.backgroundColor = "#FFC107";
                    e.currentTarget.style.boxShadow =
                        "5px 5px 15px rgba(0, 0, 0, 0.5)";
                }}
                onMouseLeave={(e) => {
                    e.currentTarget.style.backgroundColor =
                        selectedCertificateId === certificate.id
                            ? "#F9DC5C"
                            : background;
                    e.currentTarget.style.boxShadow =
                        selectedCertificateId === certificate.id
                            ? "5px 5px 10px rgba(0, 0, 0, 0.5)"
                            : "none";
                }}
            >
                <div className="text-center p-2">
                    <div className="mb-2">
                        <strong>
                            <i className="fas fa-award"></i>{" "}
                            {certificate.original_certificate}
                        </strong>
                    </div>
                    <div className="row">
                        <div className="d-flex mb-0">
                            <div className="col-6 col-lg-4 text-start text-dark">
                                <strong>User :</strong>
                            </div>
                            <p className="col-6 col-lg-8 text-start text-dark mb-0">
                                {certificate.user.first_name}{" "}
                                {certificate.user.surname}
                            </p>
                        </div>
                        <div className="d-flex mb-0">
                            <div className="col-6 col-lg-4 text-start text-dark">
                                <strong>Tanggal :</strong>
                            </div>
                            <p className="col-6 col-lg-8 text-start text-dark mb-0">
                                {certificate.created_at}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CertificateCard;
