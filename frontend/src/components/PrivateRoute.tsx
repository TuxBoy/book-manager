import React from "react";
import {getToken} from "../services/auth.ts";
import {Navigate} from "react-router-dom";

interface Props {
    children: React.ReactNode
}

export default function PrivateRoute({ children }: Props) {
    const token = getToken()
    return token ? <>{children}</> : <Navigate to={"/login"} />
}
