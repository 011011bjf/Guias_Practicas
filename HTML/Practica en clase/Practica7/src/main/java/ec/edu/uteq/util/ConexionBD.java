package ec.edu.uteq.util;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class ConexionBD {
    private static final String URLmysql = "jdbc:mysql://localhost:3306/practica7_db?useSSL=false&serverTimezone=UTC&allowPublicKeyRetrieval=true";
    private static final String URLpostgres = "jdbc:postgresql://localhost:5432/pruevabd";
    private static final String USER = "postgres";
    private static final String PASSWORD = "12345";

    public static Connection obtener() throws SQLException {
        return DriverManager.getConnection(URLpostgres, USER, PASSWORD);
         }
}
