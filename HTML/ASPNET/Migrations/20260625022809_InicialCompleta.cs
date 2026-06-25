using System;
using Microsoft.EntityFrameworkCore.Migrations;
using Npgsql.EntityFrameworkCore.PostgreSQL.Metadata;

#nullable disable

namespace ASPNET.Migrations
{
    /// <inheritdoc />
    public partial class InicialCompleta : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "Carreras",
                columns: table => new
                {
                    Id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    Nombre = table.Column<string>(type: "text", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Carreras", x => x.Id);
                });

            migrationBuilder.CreateTable(
                name: "Cursos",
                columns: table => new
                {
                    Id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    Nombre = table.Column<string>(type: "text", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Cursos", x => x.Id);
                });

            migrationBuilder.CreateTable(
                name: "Estudiantes",
                columns: table => new
                {
                    Id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    Nombre = table.Column<string>(type: "text", nullable: false),
                    Correo = table.Column<string>(type: "text", nullable: false),
                    CarreraId = table.Column<int>(type: "integer", nullable: false),
                    Creado = table.Column<DateTime>(type: "timestamp with time zone", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Estudiantes", x => x.Id);
                    table.ForeignKey(
                        name: "FK_Estudiantes_Carreras_CarreraId",
                        column: x => x.CarreraId,
                        principalTable: "Carreras",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateIndex(
                name: "IX_Estudiantes_CarreraId",
                table: "Estudiantes",
                column: "CarreraId");

            migrationBuilder.Sql(@"
                CREATE OR REPLACE FUNCTION obtener_estudiantes_paginados(
                    p_pagina INT,
                    p_tamano INT,
                    p_orden VARCHAR,
                    p_buscar VARCHAR
                )
                RETURNS SETOF ""Estudiantes"" AS $$
                BEGIN
                    RETURN QUERY
                    SELECT e.*
                    FROM ""Estudiantes"" e
                    LEFT JOIN ""Carreras"" c ON e.""CarreraId"" = c.""Id""
                    WHERE (p_buscar = '' OR e.""Nombre"" ILIKE '%' || p_buscar || '%' OR c.""Nombre"" ILIKE '%' || p_buscar || '%')
                    ORDER BY
                        CASE WHEN p_orden = 'nombre' THEN e.""Nombre"" END ASC,
                        CASE WHEN p_orden = 'carrera' THEN c.""Nombre"" END ASC,
                        CASE WHEN p_orden = 'id' OR p_orden NOT IN ('nombre', 'carrera') THEN e.""Id""::varchar END ASC
                    OFFSET (p_pagina - 1) * p_tamano LIMIT p_tamano;
                END;
                $$ LANGUAGE plpgsql;
            ");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.Sql("DROP FUNCTION IF EXISTS obtener_estudiantes_paginados(INT, INT, VARCHAR, VARCHAR);");

            migrationBuilder.DropTable(
                name: "Cursos");

            migrationBuilder.DropTable(
                name: "Estudiantes");

            migrationBuilder.DropTable(
                name: "Carreras");
        }
    }
}
