import React from "react";

function ButtonCRUD({ type, onClick, label, iconClass, color, disabled }) {
    return (
        <button
            type={type}
            className={`btn btn-md me-2 ${color}`}
            onClick={onClick}
            disabled={disabled}
        >
            <i className={iconClass}></i> {label}
        </button>
    );
}

export default ButtonCRUD;
