import React from "react";

const CardContent = ({ title, icon = null, children }) => {
    return (
        <div className="row mt-2 mb-4">
            <div className="col-12">
                <div className="card border-0 rounded shadow-sm border-top">
                    <div className="card-header">
                        {icon && <i className={`fa ${icon} mr-2`}></i>}
                        <strong style={{ fontSize: "18px" }}>
                            {" "}
                            {title}
                        </strong>{" "}
                    </div>
                    <div className="card-body">{children}</div>
                </div>
            </div>
        </div>
    );
};

export default CardContent;
