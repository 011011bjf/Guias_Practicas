<%--
  Created by IntelliJ IDEA.
  User: program
  Date: 15/06/2026
  Time: 10:27 a.m.
  To change this template use File | Settings | File Templates.
--%>
<%@ page contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="jakarta.tags.core" %>

<c:set var="ctx" value="${pageContext.request.contextPath}" />

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estudiante</title>
</head>
<body>

<h1>
    <c:choose>
        <c:when test="${estudiante != null}">
            Editar estudiante
        </c:when>
        <c:otherwise>
            Nuevo estudiante
        </c:otherwise>
    </c:choose>
</h1>

<form method="post" action="${ctx}/estudiantes">

    <input type="hidden" name="id" value="${estudiante.id}" />

    <p>
        Nombre:
        <input
                type="text"
                name="nombre"
                value="${estudiante.nombre}"
                required />
    </p>

    <p>
        Correo:
        <input
                type="email"
                name="correo"
                value="${estudiante.correo}"
                required />
    </p>

    <p>
        Carrera:
        <input
                type="text"
                name="carrera"
                value="${estudiante.carrera}"
                required />
    </p>

    <button type="submit">Guardar</button>

    <a href="${ctx}/estudiantes">
        Cancelar
    </a>

</form>

</body>
</html>
