import React from "react";

const InfoCard = ({ icon, background, value, title, subtitle }) => {
    const isBackgroundHashed = background.startsWith("#");

    return (
        <div className="col-12 col-lg-3 mb-4 mx-auto">
            <div className="card border-0 rounded-3 shadow-sm overflow-hidden transition-all">
                <div className="card-body p-0 d-flex align-items-center">
                    <div
                        className={`py-4 px-5 mfe-3 text-value ${
                            isBackgroundHashed ? background : `bg-${background}`
                        }`}
                        style={{
                            width: "130px",
                            backgroundColor: isBackgroundHashed
                                ? background
                                : `#${background}`,
                            display: "flex",
                            alignItems: "center",
                            justifyContent: "center",
                        }}
                    >
                        <i className={`fas ${icon} fa-2x text-white`}></i>
                    </div>
                    <div className="px-3">
                        <div
                            className="h4 mb-0 fw-bold"
                            style={{ color: "var(--font-color)" }}
                        >
                            {value}
                        </div>
                        <div className="text-uppercase font-weight-bold small text-muted">
                            {title}
                        </div>
                        {subtitle && (
                            <div className="small text-muted mt-1" style={{ fontSize: "0.72rem" }}>
                                <i className="fas fa-info-circle me-1"></i>
                                {subtitle}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default InfoCard;
