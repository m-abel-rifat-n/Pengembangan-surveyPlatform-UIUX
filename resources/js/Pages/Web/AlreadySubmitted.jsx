import React, { useEffect } from "react";
import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/inertia-react";
import Swal from "sweetalert2";

export default function AlreadySubmitted() {
    const { message } = usePage().props;

    useEffect(() => {
        Swal.fire({
            icon: "error",
            title: "Oops!",
            text: message || "You have already submitted this survey and cannot participate again.",
            confirmButtonColor: "#3085d6",
            allowOutsideClick: false,
        }).then(() => {
            Inertia.visit("/surveys");
        });
    }, []);

    return null;
}
