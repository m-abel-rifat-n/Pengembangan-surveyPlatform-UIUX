import React, { useState } from "react";
import { Collapse } from "react-bootstrap";
import { CaretDownFill, CaretRightFill } from "react-bootstrap-icons";

const AccordionLayout = ({ title, children, defaultOpen = false }) => {
    const [isOpen, setIsOpen] = useState(defaultOpen);

    const toggleAccordion = () => {
        setIsOpen(!isOpen);
    };

    return (
        <div className="accordion accordion-style">
            <div className="card">
                <div className="card border-0 rounded shadow-sm border-top">
                    <div className="card-header accordion-header" onClick={toggleAccordion}>
                        <h5 className="mb-0 d-flex align-items-center justify-content-between accordion-title">
                            <div className="accordion-title">{title}</div>
                            {isOpen ? (
                                <CaretDownFill
                                    size={20}
                                    className="accordion-icon"
                                />
                            ) : (
                                <CaretRightFill
                                    size={20}
                                    className="accordion-icon"
                                />
                            )}
                        </h5>
                    </div>
                    <Collapse in={isOpen}>
                        <div className="card-body">{children}</div>
                    </Collapse>
                </div>
            </div>
        </div>
    );
};

export default AccordionLayout;
