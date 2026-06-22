package edu.uteq.guia_java_practicaexperimental;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.List;

@SpringBootApplication
public class GuiaJavaPracticaExperimentalApplication {

    public static void main(String[] args) {
        loadDotEnv();
        SpringApplication.run(GuiaJavaPracticaExperimentalApplication.class, args);
    }

    private static void loadDotEnv() {
        try {
            if (Files.exists(Paths.get(".env"))) {
                List<String> lines = Files.readAllLines(Paths.get(".env"));
                for (String line : lines) {
                    line = line.trim();
                    if (line.isEmpty() || line.startsWith("#")) {
                        continue;
                    }
                    int idx = line.indexOf('=');
                    if (idx > 0) {
                        String key = line.substring(0, idx).trim();
                        String val = line.substring(idx + 1).trim();
                        if (val.startsWith("\"") && val.endsWith("\"")) {
                            val = val.substring(1, val.length() - 1);
                        } else if (val.startsWith("'") && val.endsWith("'")) {
                            val = val.substring(1, val.length() - 1);
                        }
                        System.setProperty(key, val);
                    }
                }
            }
        } catch (IOException e) {
            System.err.println("Could not load .env file: " + e.getMessage());
        }
    }
}
