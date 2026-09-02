-- Indexes for merged_output_d_20260122
-- These only apply if the table already exists (e.g., after data import).
-- Uses a procedure to safely skip if the table is not yet present.

DELIMITER //
CREATE PROCEDURE IF NOT EXISTS add_naturecan_indexes()
BEGIN
    IF EXISTS (SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'merged_output_d_20260122') THEN
        SET @t = 'merged_output_d_20260122';

        IF NOT EXISTS (SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @t AND INDEX_NAME = 'idx_plant_name') THEN
            CREATE INDEX idx_plant_name ON merged_output_d_20260122 (plant_name);
        END IF;
        IF NOT EXISTS (SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @t AND INDEX_NAME = 'idx_title') THEN
            CREATE INDEX idx_title ON merged_output_d_20260122 (title);
        END IF;
        IF NOT EXISTS (SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @t AND INDEX_NAME = 'idx_cancer_types') THEN
            CREATE INDEX idx_cancer_types ON merged_output_d_20260122 (cancer_types);
        END IF;
        IF NOT EXISTS (SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @t AND INDEX_NAME = 'idx_study_types') THEN
            CREATE INDEX idx_study_types ON merged_output_d_20260122 (study_types);
        END IF;
        IF NOT EXISTS (SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @t AND INDEX_NAME = 'idx_model_system') THEN
            CREATE INDEX idx_model_system ON merged_output_d_20260122 (model_system);
        END IF;
        IF NOT EXISTS (SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @t AND INDEX_NAME = 'idx_experimental_techniques') THEN
            CREATE INDEX idx_experimental_techniques ON merged_output_d_20260122 (experimental_techniques);
        END IF;
        IF NOT EXISTS (SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @t AND INDEX_NAME = 'idx_toxicity_and_side_effects') THEN
            CREATE INDEX idx_toxicity_and_side_effects ON merged_output_d_20260122 (toxicity_and_side_effects);
        END IF;
        IF NOT EXISTS (SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = @t AND INDEX_NAME = 'idx_pmid') THEN
            CREATE INDEX idx_pmid ON merged_output_d_20260122 (pmid);
        END IF;
    END IF;
END //
DELIMITER ;

CALL add_naturecan_indexes();
DROP PROCEDURE IF EXISTS add_naturecan_indexes;
