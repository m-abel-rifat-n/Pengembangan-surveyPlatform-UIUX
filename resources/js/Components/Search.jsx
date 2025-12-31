import React, { useState } from "react";
import { Inertia } from "@inertiajs/inertia";

export default function Search({ URL }) {
    const [search, setSearch] = useState("");

    const searchHandler = (e) => {
        e.preventDefault();
        Inertia.get(`${URL}?q=${search}`);
    };

    return (
        <>
            <form onSubmit={searchHandler}>
                <div className="input-group">
                    <input
                        type="text"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        className="form-control border-0 shadow-sm"
                        placeholder="type keywords and press enter..."
                    />
                    <span
                        className="input-group-text-search border-0 shadow-sm"
                        style={{ cursor: "pointer" }}
                        onClick={searchHandler} 
                    >
                        <i className="fa fa-search"></i>
                    </span>
                </div>
            </form>
        </>
    );
}
